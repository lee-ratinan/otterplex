<?php

namespace App\Controllers;

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
                    return $this->response->setJSON([
                        'status'    => STATUS_RESPONSE_OK,
                        'message'   => lang('System.response-msg.success.data-saved'),
                    ]);
                }
                $error_msg = lang('System.response-msg.error.db-issue');
            }

            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $error_msg
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ]);
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
        return view('admin/my-businesses', $data);
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
            ]);
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
}