<?php

namespace App\Controllers;

use App\Models\BusinessMasterModel;
use App\Models\BusinessTypeModel;
use App\Models\BusinessUserModel;
use App\Models\UserMasterModel;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{

    /**
     * Dashboard page
     * @return string
     */
    public function index(): string
    {
        $data    = [
            'slug'         => 'dashboard',
            'lang'         => $this->request->getLocale(),
        ];
        return view('admin/dashboard', $data);
    }

    /**
     * Profile page
     * @return string
     */
    public function profile(): string
    {
        $data    = [
            'slug'         => 'profile',
            'lang'         => $this->request->getLocale(),
        ];
        return view('admin/profile', $data);
    }

    /**
     * Save/update profile data
     * @return ResponseInterface
     */
    public function profile_post(): ResponseInterface
    {
        try {
            $session         = session();
            $userMasterModel = new UserMasterModel();
            $script_action   = $this->request->getPost('script_action');
            $available_lang  = get_available_locales();
            $error_msg       = lang('System.response-msg.error.generic');
            if ('save_profile' == $script_action) {
                $telephone_number   = $this->request->getPost('telephone_number') ?? null;
                $lang_code          = $this->request->getPost('lang_code');
                $user_gender        = $this->request->getPost('user_gender') ?? null;
                $user_date_of_birth = $this->request->getPost('user_date_of_birth') ?? null;
                $user_nationality   = $this->request->getPost('user_nationality') ?? null;
                $profile_status_msg = $this->request->getPost('profile_status_msg') ?? null;
                if (empty($lang_code) || !isset($available_lang[$lang_code])) {
                    $lang_code      = 'en'; // Always the default if empty - no matter what
                }
                $data = [
                    'telephone_number'   => $telephone_number,
                    'lang_code'          => $lang_code,
                    'user_gender'        => $user_gender,
                    'user_date_of_birth' => $user_date_of_birth,
                    'user_nationality'   => $user_nationality,
                    'profile_status_msg' => $profile_status_msg,
                ];
                if ($userMasterModel->update($session->user_id, $data)) {
                    $user = $userMasterModel->find($session->user_id);
                    unset($user['password_hash']);
                    $session->set('user', $user);
                    $session->set('lang', $lang_code);
                    return $this->response->setJSON([
                        'status'    => STATUS_RESPONSE_OK,
                        'message'   => lang('System.response-msg.success.data-saved'),
                    ]);
                }
                $error_msg = lang('System.response-msg.error.db-issue');
            } elseif ('change_password' == $script_action) {
                $current_password = $this->request->getPost('current_password');
                $new_password     = $this->request->getPost('new_password');
                $confirm_password = $this->request->getPost('confirm_password');
                if ($new_password != $confirm_password || empty($new_password)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.password-failed') . ' [VR]',
                    ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }
                $result = $userMasterModel->updatePassword($session->user_id, $new_password, $current_password);
                if ('OK' == $result) {
                    return $this->response->setJSON([
                        'status'    => STATUS_RESPONSE_OK,
                        'message'   => lang('System.response-msg.success.password-changed'),
                    ]);
                }
                $error_msg = lang('System.response-msg.error.password-failed') . ' ' . $result;
            } elseif ('upload_avatar' == $script_action) {
                helper(['form']);
                $validationRule = [
                    'avatar' => [
                        'label' => lang('Admin.profile.avatar'),
                        'rules' => [
                            'uploaded[avatar]',
                            'is_image[avatar]',
                            'mime_in[avatar,image/jpg,image/jpeg,image/png]',
                            'max_size[avatar,200]',
                            'max_dims[avatar,1024,1024]',
                        ],
                    ],
                ];
                if (!$this->validateData([], $validationRule)) {
                    $errors = $this->validator->getErrors();
                    $toast  = lang('System.response-msg.error.upload-failed');
                    foreach ($errors as $error) {
                        $toast .= '<br>- ' . $error;
                    }
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => $toast
                    ]);
                }
                $img                  = $this->request->getFile('avatar');
                list($width, $height) = getimagesize($img->getPathname());
                $side                 = round(min($width, $height));
                $file_type            = $img->getClientMimeType();
                $email_address        = $session->user['email_address'];
                $file_name            = 'profile_' . preg_replace('/[^a-z0-9]/i', '', strtolower($email_address)) . '.jpg';
                if ('image/png' == $file_type) {
                    $source = imagecreatefrompng($img->getPathname());
                } else {
                    $source = imagecreatefromjpeg($img->getPathname());
                }
                $destination = imagecreatetruecolor($side, $side);
                $x           = round(($width - $side) / 2);
                $y           = round(($height - $side) / 2);
                imagecopyresampled($destination, $source, 0, 0, $x, $y, $side, $side, $side, $side);
                imagejpeg($destination, WRITEPATH . 'uploads/profile_pictures/' . $file_name, 90);
                imagedestroy($source);
                imagedestroy($destination);
                $session->set(['avatar' => retrieve_avatars($session->user['email_address'], $session->full_name)]);
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.uploaded')
                ]);
            } elseif ('remove_avatar' == $script_action) {
                $email_address = $session->user['email_address'];
                $file_name     = 'profile_' . preg_replace('/[^a-z0-9]/i', '', strtolower($email_address)) . '.jpg';
                $file_path     = WRITEPATH . 'uploads/profile_pictures/' . $file_name;
                if (file_exists($file_path)) {
                    if (unlink($file_path)) {
                        $session->set(['avatar' => retrieve_avatars($session->user['email_address'], $session->full_name)]);
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.removed')
                        ]);
                    }
                }
                $error_msg  = lang('System.response-msg.error.removed');
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $error_msg
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * My Businesses page
     * @return string
     */
    public function my_businesses(): string
    {
        $session           = session();
        $businessUserModel = new BusinessUserModel();
        $myBusinesses      = $businessUserModel->getBusinessesByUserId($session->user_id);
        $data              = [
            'slug'         => 'my-businesses',
            'lang'         => $this->request->getLocale(),
            'myBusinesses' => $myBusinesses
        ];
        return view('admin/my_businesses', $data);
    }

    /**
     * Switch current business
     * @return ResponseInterface
     */
    public function switch_business(): ResponseInterface
    {
        $session              = session();
        $businessUserModel    = new BusinessUserModel();
        $target_business_slug = $this->request->getPost('target_business_slug');
        $business             = $businessUserModel->getBusinessesByUserId($session->user_id, true, $target_business_slug);
        if (!$business) {
            return $this->response->setJSON([
                'status' => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.business-inactive')
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
        $session->set([
            'user_role' => $business[0]['user_role'],
            'business'  => $business[0]
        ]);
        return $this->response->setJSON([
            'status' => STATUS_RESPONSE_OK,
            'message' => lang('System.response-msg.success.business-switched')
        ]);
    }

    /**
     * About page
     * @return string
     */
    public function about(): string
    {
        $data    = [
            'slug'         => 'about',
            'lang'         => $this->request->getLocale(),
        ];
        return view('admin/about', $data);
    }

    /**
     * Manage business page
     * @return string
     */
    public function business(): string
    {
        $session             = session();
        if ('OWNER' != $session->user_role) {
            $data = [
                'slug' => 'business',
                'lang' => $this->request->getLocale(),
            ];
            return view('admin/_forbidden', $data);
        }
        $businessMasterModel = new BusinessMasterModel();
        $businessTypeModel   = new BusinessTypeModel();
//        $businessUserModel   = new BusinessUserModel();
        $businessId          = $session->business['id'];
        $business            = $businessMasterModel->find($businessId);
        $businessTypes       = $businessTypeModel->findAll();
//        $businessUsers       = $businessUserModel->getUsersByBusinessId($businessId);
        $allLanguages        = get_available_locales('long');
        // Fix JSON
        $business['business_local_names'] = json_decode($business['business_local_names'], true);
        for ($i = 0; $i < count($businessTypes); $i++) {
            $businessTypes[$i]['type_local_names'] = json_decode($businessTypes[$i]['type_local_names'], true);
        }
        // DATA
        $data                = [
            'slug'           => 'business',
            'lang'           => $this->request->getLocale(),
            'business'       => $business,
            'business_types' => $businessTypes,
//            'business_users' => $businessUsers,
            'all_languages'  => $allLanguages
        ];
        return view('admin/business', $data);
    }

    /**
     * Manage branch
     * @return string
     */
    public function business_branch(): string
    {
        $data = [
            'slug'           => 'business-branch',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/business_branch', $data);
    }

    /**
     * Manage staff
     * @return string
     */
    public function business_user(): string
    {
        $data = [
            'slug'           => 'business-user',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/business_user', $data);
    }

    /**
     * Manage customer
     * @return string
     */
    public function business_customer(): string
    {
        $data = [
            'slug'           => 'business-customer',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/business_customer', $data);
    }

    /**
     * Manage order
     * @return string
     */
    public function order(): string
    {
        $data = [
            'slug'           => 'order',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/order', $data);
    }

    /**
     * Manage service
     * @return string
     */
    public function service(): string
    {
        $data = [
            'slug'           => 'service',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/service', $data);
    }

    /**
     * Manage product
     * @return string
     */
    public function product(): string
    {
        $data = [
            'slug'           => 'product',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/product', $data);
    }

    /**
     * Manage product category
     * @return string
     */
    public function product_category(): string
    {
        $data = [
            'slug'           => 'product-category',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/product_category', $data);
    }

    /**
     * Manage blog
     * @return string
     */
    public function blog(): string
    {
        $data = [
            'slug'           => 'blog',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/blog', $data);
    }
}