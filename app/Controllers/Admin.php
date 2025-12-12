<?php

namespace App\Controllers;

use App\Models\BranchMasterModel;
use App\Models\BranchModifiedHoursModel;
use App\Models\BranchOpeningHoursModel;
use App\Models\BranchUserModel;
use App\Models\BusinessContractModel;
use App\Models\BusinessContractPaymentModel;
use App\Models\BusinessCustomerModel;
use App\Models\BusinessMasterModel;
use App\Models\BusinessTypeModel;
use App\Models\BusinessUserModel;
use App\Models\OtternautPackageModel;
use App\Models\ResourceMasterModel;
use App\Models\ResourceTypeModel;
use App\Models\UserMasterModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use DateMalformedStringException;
use DateTime;
use function PHPUnit\Framework\throwException;

class Admin extends BaseController
{

    /**
     * Use to return forbidden page/JSON
     * @param string $type
     * @return ResponseInterface|string
     */
    private function forbiddenResponse(string $type): ResponseInterface|string
    {
        if ('string' == $type) {
            $data = [
                'slug' => 'business',
                'lang' => $this->request->getLocale(),
            ];
            return view('admin/_forbidden', $data);
        } elseif ('ResponseInterface' == $type) {
            return $this->response->setJSON([
                'success' => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.no-permission')
            ]);
        }
        // DataTable
        return $this->response->setJSON([
            'draw'            => $this->request->getPost("draw"),
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => [],
            'error'           => lang('System.response-msg.error.no-permission')
        ]);
    }

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
                            'max_size[avatar,400]',
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
            return $this->forbiddenResponse('string');
        }
        $businessMasterModel  = new BusinessMasterModel();
        $businessTypeModel    = new BusinessTypeModel();
        $contractModel        = new BusinessContractModel();
        $businessId           = $session->business['id'];
        $business             = $businessMasterModel->find($businessId);
        $businessTypes        = $businessTypeModel->retrieveData();
        $contracts            = $contractModel->retrieveDataByBusinessId($businessId);
        $allLanguages         = get_available_locales('long');
        $logo_file            = base_url('assets/img/logo.png');
        if (!empty($business['business_logo'])) {
            $logo_file = base_url('file/business_' . $business['business_logo']);
        }
        // DATA
        $business['business_local_names'] = json_decode($business['business_local_names'], true);
        $data                = [
            'slug'           => 'business',
            'lang'           => $this->request->getLocale(),
            'business'       => $business,
            'business_types' => $businessTypes,
            'contracts'      => $contracts,
            'all_languages'  => $allLanguages,
            'logo_file'      => $logo_file
        ];
        return view('admin/business', $data);
    }

    /**
     * Handle saving businesses
     * - business_master
     * - upload/remove logo
     * @return ResponseInterface
     */
    public function business_post(): ResponseInterface
    {
        $session = session();
        if ('OWNER' != $session->user_role) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $session             = session();
            $businessId          = $session->business['id'];
            $businessMasterModel = new BusinessMasterModel();
            $script_action       = $this->request->getPost('script_action');
            $available_lang      = get_available_locales();
            $error_msg           = lang('System.response-msg.error.generic');
            if ('save_business' == $script_action) {
                $fields      = ['business_type_id', 'business_name', 'business_slug', 'tax_percentage', 'tax_inclusive', 'mart_primary_color', 'mart_text_color', 'mart_background_color'];
                $data        = [];
                foreach ($available_lang as $code => $language_name) {
                    $fields[]      = 'business_local_names_' . $code;
                }
                foreach ($fields as $field) {
                    $data[$field]     = $this->request->getPost($field);
                    if (in_array($field, ['mart_primary_color', 'mart_text_color', 'mart_background_color'])) {
                        $data[$field] = str_replace('#', '', $data[$field]);
                    }
                }
                // Fix JSON field
                $business_local_names_values = [];
                foreach ($available_lang as $code => $language_name) {
                    $business_local_names_values[$code] = $data['business_local_names_' . $code];
                }
                $data['business_local_names'] = json_encode($business_local_names_values);
                // Save
                if ($businessMasterModel->update($businessId, $data)) {
                    // Reset business
                    $business                         = $businessMasterModel->find($businessId);
                    $business['business_local_names'] = json_decode($business['business_local_names'], true);
                    $session->set('business', $business);
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved')
                    ]);
                }
            } else if ('upload_logo' == $script_action) {
                $business_slug = $session->business['business_slug'];
                helper(['form']);
                $validationRule = [
                    'logo' => [
                        'label' => lang('Business.logo'),
                        'rules' => [
                            'uploaded[logo]',
                            'is_image[logo]',
                            'mime_in[logo,image/jpg,image/jpeg,image/png]',
                            'max_size[logo,600]',
                            'max_dims[logo,1280,960]',
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
                $img                  = $this->request->getFile('logo');
                list($width, $height) = getimagesize($img->getPathname());
                $file_type            = $img->getClientMimeType();
                $file_name            = 'logo_' . $business_slug . '.jpg';
                if ($file_type === 'image/png') {
                    $source = imagecreatefrompng($img->getPathname());
                } else {
                    $source = imagecreatefromjpeg($img->getPathname());
                }
                // --- Target dimensions ---
                $targetW     = 1280;
                $targetH     = 960;
                $targetRatio = $targetW / $targetH;
                // --- Step 1: scale the image proportionally so that it is >= target size ---
                $srcRatio    = $width / $height;
                // If image is wider relative to height → height is limiting
                if ($srcRatio > $targetRatio) {
                    // Height determines scale
                    $scaledH = $targetH;
                    $scaledW = intval($targetH * $srcRatio);
                } else {
                    // Width determines scale
                    $scaledW = $targetW;
                    $scaledH = intval($targetW / $srcRatio);
                }
                $scaled = imagecreatetruecolor($scaledW, $scaledH);
                imagecopyresampled($scaled, $source, 0, 0, 0, 0, $scaledW, $scaledH, $width, $height);
                // --- Step 2: crop the center to 1280 × 960 ---
                $cropX = intval(($scaledW - $targetW) / 2);
                $cropY = intval(($scaledH - $targetH) / 2);
                $final = imagecreatetruecolor($targetW, $targetH);
                imagecopyresampled($final, $scaled, 0, 0, $cropX, $cropY, $targetW, $targetH, $targetW, $targetH);
                imagejpeg($final, WRITEPATH . 'uploads/business_logos/' . $file_name, 90);
                // Update database & session
                $session->set('business_logo', base_url('file/business_' . $file_name));
                $businessMasterModel->update($businessId, ['business_logo' => $file_name]);
                // --- Cleanup ---
                imagedestroy($source);
                imagedestroy($scaled);
                imagedestroy($final);
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.uploaded')
                ]);
            } else if ('remove_logo' == $script_action) {
                $business      = $businessMasterModel->find($businessId);
                $file_name     = $business['business_logo'];
                $file_path     = WRITEPATH . 'uploads/business_logos/' . $file_name;
                if (!empty($file_name) && file_exists($file_path)) {
                    if (unlink($file_path)) {
                        // Update database & session
                        $session->set('business_logo', '');
                        $businessMasterModel->update($businessId, ['business_logo' => null]);
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.removed')
                        ]);
                    }
                }
                $error_msg = lang('System.response-msg.error.removed');
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
     * Manage business contract
     * @return string
     * @throws DateMalformedStringException
     */
    public function business_contract_renewal(): string
    {
        $session       = session();
        if ('OWNER' != $session->user_role) {
            return $this->forbiddenResponse('string');
        }
        $businessContractModel = new BusinessContractModel();
        $businessId            = $session->business['id'];
        $unpaidContract        = $businessContractModel->select('business_contract.*, package_name')
            ->join('otternaut_package', 'otternaut_package.id = business_contract.package_id')
            ->where('financial_status', 'PENDING')->where('business_id', $businessId)->first();
        if ($unpaidContract) {
            $paymentModel = new BusinessContractPaymentModel();
            $payments     = $paymentModel->where('contract_id', $unpaidContract['id'])->findAll();
            $data         = [
                'slug'           => 'business-contract-renewal',
                'lang'           => $this->request->getLocale(),
                'breadcrumb'     => [
                    [
                        'url'        => base_url('admin/business'),
                        'page_title' => lang('Admin.pages.business'),
                    ]
                ],
                'unpaid_pending' => lang('Business.has-unpaid-contract'),
                'record'         => $unpaidContract,
                'payments'       => $payments,
            ];
            return view('admin/business_contract_renewal', $data);
        }
        $packageModel  = new OtternautPackageModel();
        $countryCode   = $session->business['country_code'];
        $packages      = $packageModel->getOtternautPackageForCountry($countryCode);
        $final         = [];
        $today         = date(DATE_FORMAT_DB);
        $currentExpiry = date(DATE_FORMAT_DB, strtotime($session->business['contract_expiry']));
        if ($currentExpiry && $currentExpiry > $today) {
            $baseDate = $currentExpiry;   // still active → extend from expiry
        } else {
            $baseDate = $today;           // expired or null → extend from today
        }
        $expiryMonthly = calculate_bill_cycle($baseDate, $session->business['contract_anchor_day']);
        $expiryYearly  = calculate_bill_cycle($baseDate, $session->business['contract_anchor_day'], 'year');
        foreach ($packages as $package) {
            $final['month'][] = [
                'id'                  => $package['id'],
                'package_name'        => $package['package_name'],
                'package_price'       => $package['package_monthly_price'],
                'package_validity'    => lang('Business.packages.validity.month'),
                'package_start_date'  => $baseDate,
                'package_expiry_date' => $expiryMonthly,
            ];
            $final['year'][] = [
                'id'                  => $package['id'],
                'package_name'        => $package['package_name'],
                'package_price'       => $package['package_annual_price'],
                'package_validity'    => lang('Business.packages.validity.year'),
                'package_start_date'  => $baseDate,
                'package_expiry_date' => $expiryYearly,
            ];
        }
        $data         = [
            'slug'       => 'business-contract-renewal',
            'lang'       => $this->request->getLocale(),
            'breadcrumb' => [
                [
                    'url'        => base_url('admin/business'),
                    'page_title' => lang('Admin.pages.business'),
                ]
            ],
            'packages'   => $final
        ];
        return view('admin/business_contract_renewal', $data);
    }

    /**
     * Create a new contract
     * @return ResponseInterface
     */
    public function business_contract_renewal_post(): ResponseInterface
    {
        $session = session();
        if ('OWNER' != $session->user_role) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $fields = ['contract_start', 'contract_expiry', 'total_amount', 'package_id'];
            $data   = [];
            foreach ($fields as $field) {
                $data[$field]     = $this->request->getPost($field);
            }
            $data['business_id']      = $session->business['id'];
            $data['invoice_number']   = calculate_invoice_number();
            $data['invoiced_amount']  = $data['total_amount'];
            $data['discount_amount']  = 0;
            $data['paid_amount']      = 0;
            $businessContractModel    = new BusinessContractModel();
            $data['financial_status'] = $businessContractModel::FINANCIAL_STATUS_PENDING;
            if ($businessContractModel->insert($data)) {
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.contract-renewal-done')
                ]);
            }
            return $this->response->setJSON([
                'success' => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.generic')
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Manage branch
     * @return string
     */
    public function business_branch(): string
    {
        $session       = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $data = [
            'slug'           => 'business-branch',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/business_branch', $data);
    }

    /**
     * Get data for business branch
     * @return ResponseInterface
     */
    public function business_branch_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('DataTable');
        }
        $branchModel = new BranchMasterModel();
        $branches    = $branchModel->getDataTable();
        return $this->response->setJSON($branches);
    }

    /**
     * Manage branch
     * @param string $branch_slug
     * @return RedirectResponse|string
     */
    public function business_branch_manage(string $branch_slug): RedirectResponse|string
    {
        $session       = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $branchModel   = new BranchMasterModel();
        $hoursModel    = new BranchOpeningHoursModel();
        $modifiedModel = new BranchModifiedHoursModel();
        $mode          = 'new';
        $branch        = [];
        $hours         = [
            'M'  => [0, null, null],
            'T'  => [0, null, null],
            'W'  => [0, null, null],
            'TH' => [0, null, null],
            'F'  => [0, null, null],
            'S'  => [0, null, null],
            'SU' => [0, null, null],
        ];
        $modified      = [];
        $allLanguages  = get_available_locales('long');
        if ('new-branch' !== $branch_slug) {
            $branch = $branchModel
                ->where('business_id', $session->business['id'])
                ->where('branch_slug', $branch_slug)->first();
            if (!$branch) {
                return redirect('admin/business/branch');
            }
            $branch['branch_local_names'] = json_decode($branch['branch_local_names'], true);
            // OTHER INFO
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $hour_raw  = $hoursModel->where('branch_id', $branch['id'])->findAll();
            $modified  = $modifiedModel
                ->where('branch_id', $branch['id'])
                ->where('modified_hours_date >=', $yesterday)
                ->orderBy('modified_hours_date', 'ASC')->findAll();
            foreach ($hour_raw as $hour) {
                $hours[$hour['day_of_the_week']] = [$hour['id'], $hour['opening_hours'], $hour['closing_hours']];
            }
            // FIX MODE
            $mode      = 'edit';
        }
        // OPTIONS
        $subdivisions = get_country_codes()['subdivisions'][$session->business['country_code']];
        $timezones    = get_tzdb_by_country($session->business['country_code']);
        $data         = [
            'slug'          => 'business-branch-manage',
            'lang'          => $this->request->getLocale(),
            'branch'        => $branch,
            'mode'          => $mode,
            'hours'         => $hours,
            'modified'      => $modified,
            'subdivisions'  => $subdivisions,
            'all_languages' => $allLanguages,
            'timezones'     => $timezones,
            'breadcrumb'    => [
                [
                    'url'        => base_url('admin/business/branch'),
                    'page_title' => lang('Admin.pages.business-branch'),
                ]
            ]
        ];
        return view('admin/business_branch_manage', $data);
    }

    /**
     * Manage staff
     * @return string
     */
    public function business_user(): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $data = [
            'slug'           => 'business-user',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/business_user', $data);
    }

    /**
     * Get users in the business
     * @return ResponseInterface
     */
    public function business_user_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('DataTable');
        }
        $staffModel = new BusinessUserModel();
        $users      = $staffModel->getDataTable();
        return $this->response->setJSON($users);
    }

    /**
     * @param int $userId
     * @return string
     */
    public function business_user_manage(int $userId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $businessId    = $session->business['id'];
        $userId        = (int) $userId / ID_MASKED_PRIME;
        $userModel     = new UserMasterModel();
        $BusinessModel = new BusinessUserModel();
        $BranchModel   = new BranchUserModel();
        $mode          = 'new';
        $user          = [];
        $businessUser  = [];
        $branchUser    = [];
        if (0 < $userId) {
            $mode         = 'edit';
            $user         = $userModel->find($userId);
            if (!empty($user)) {
                $businessUser = $BusinessModel->where('user_id', $userId)->where('business_id', $businessId)->findAll();
                $branchUser   = $BranchModel->getUserByBusinessId($userId, $businessId);
            } else {
                throw new PageNotFoundException(lang('Admin.pages.page-not-found'));
            }
        }
        $data = [
            'slug'         => 'business-user-manage',
            'lang'         => $this->request->getLocale(),
            'mode'         => $mode,
            'user'         => $user,
            'businessUser' => $businessUser,
            'branchUser'   => $branchUser,
            'breadcrumb'   => [
                [
                    'url'        => base_url('admin/business/user'),
                    'page_title' => lang('Admin.pages.business-user'),
                ]
            ]
        ];
        return view('admin/business_user_management', $data);
    }
    /**
     * Manage customer
     * @return string
     */
    public function business_customer(): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $data = [
            'slug'           => 'business-customer',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/business_customer', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function business_customer_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('DataTable');
        }
        $draw      = $this->request->getPost('draw');
        $offset    = $this->request->getPost('start');
        $length    = $this->request->getPost('length');
        $search    = $this->request->getPost('search');
        $search    = $search['value'];
        $order     = $this->request->getPost('order');
        $orderBy   = $order[0]['column'] ?? 0;
        $orderDir  = $order[0]['dir'] ?? 'asc';
        $custModel = new BusinessCustomerModel();
        $users     = $custModel->getDataTable($draw, $offset, $length, $search, $orderBy, $orderDir);
        return $this->response->setJSON($users);
    }

    /**
     * Manage resource type
     * @return string
     */
    public function resource_type(): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $data = [
            'slug'           => 'resource-type',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/resource_type', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function resource_type_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('DataTable');
        }
        $typeModel = new ResourceTypeModel();
        $types     = $typeModel->getDataTable();
        return $this->response->setJSON($types);
    }

    /**
     * Manage resource type manage
     * @param int $resourceTypeId
     * @return string
     */
    public function resource_type_manage(int $resourceTypeId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $typeModel      = new ResourceTypeModel();
        $resourceType   = [];
        $resourceTypeId = $resourceTypeId / ID_MASKED_PRIME;
        if (0 < $resourceTypeId) {
            $resourceType = $typeModel
                ->where('id', $resourceTypeId)
                ->where('business_id', $session->business['id'])
                ->first();
            if (empty($resourceType)) {
                throw new PageNotFoundException(lang('Admin.pages.page-not-found'));
            }
            $resourceType['resource_local_names'] = json_decode($resourceType['resource_local_names'], true);
        }
        $data           = [
            'slug'         => 'resource-type-manage',
            'lang'         => $this->request->getLocale(),
            'resourceType' => $resourceType,
            'breadcrumb'   => [
                [
                    'url'        => base_url('admin/resource/type'),
                    'page_title' => lang('Admin.pages.resource-type'),
                ]
            ]
        ];
        return view('admin/resource_type_manage', $data);
    }

    /**
     * Manage resource
     * @return string
     */
    public function resource(): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $data = [
            'slug'           => 'resource',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/resource', $data);
    }

    /**
     * @return ResponseInterface
     */
    public function resource_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('DataTable');
        }
        $draw      = $this->request->getPost('draw');
        $offset    = $this->request->getPost('start');
        $length    = $this->request->getPost('length');
        $search    = $this->request->getPost('search');
        $search    = $search['value'];
        $order     = $this->request->getPost('order');
        $orderBy   = $order[0]['column'] ?? 0;
        $orderDir  = $order[0]['dir'] ?? 'asc';
        $typeModel = new ResourceMasterModel();
        $types     = $typeModel->getDataTable($draw, $offset, $length, $search, $orderBy, $orderDir);
        return $this->response->setJSON($types);
    }

    /**
     * Manage resource manage
     * @param int $resourceId
     * @return string
     */
    public function resource_manage(int $resourceId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $typeModel     = new ResourceTypeModel();
        $resourceModel = new ResourceMasterModel();
        $branchModel   = new BranchMasterModel();
        $resource      = [];
        $resourceId    = $resourceId / ID_MASKED_PRIME;
        if (0 < $resourceId) {
            $resource = $resourceModel->where('id', $resourceId)->first();
            if (empty($resource)) {
                throw new PageNotFoundException(lang('Admin.pages.page-not-found'));
            }
        }
        $typesRaw      = $typeModel->where('business_id', $session->business['id'])->findAll();
        $branchRaw     = $branchModel->where('business_id', $session->business['id'])->findAll();
        $types         = [];
        $branches      = [];
        $branchIds     = [];
        foreach ($typesRaw as $type) {
            $local_names        = json_decode($type['resource_local_names'], true);
            $types[$type['id']] = $local_names[$session->lang] ?? $type['resource_type'];
        }
        foreach ($branchRaw as $branch) {
            $local_names             = json_decode($branch['branch_local_names'], true);
            $branches[$branch['id']] = $local_names[$session->lang] ?? $branch['branch_name'];
            $branchIds[]             = $branch['id'];
        }
        if (!empty($resource) && !in_array($resource['branch_id'], $branchIds)) {
            throw new PageNotFoundException(lang('Admin.pages.page-not-found'));
        }
        $data     = [
            'slug'       => 'resource-manage',
            'lang'       => $this->request->getLocale(),
            'resource'   => $resource,
            'types'      => $types,
            'branches'   => $branches,
            'breadcrumb' => [
                [
                    'url'        => base_url('admin/resource'),
                    'page_title' => lang('Admin.pages.resource'),
                ]
            ]
        ];
        return view('admin/resource_manage', $data);
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
     * Manage staff allocation
     * @return string
     */
    public function allocation_staff(): string
    {
        $data = [
            'slug'           => 'allocation-staff',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/allocation_staff', $data);
    }

    /**
     * Manage staff allocation
     * @return string
     */
    public function allocation_resource(): string
    {
        $data = [
            'slug'           => 'allocation-resource',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/allocation_resource', $data);
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
     * Manage review
     * @return string
     */
    public function review(): string
    {
        $data = [
            'slug'           => 'review',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/review', $data);
    }

    /**
     * Manage discount
     * @return string
     */
    public function discount(): string
    {
        $data = [
            'slug'           => 'discount',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/discount', $data);
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

    /**
     * Manage blog category
     * @return string
     */
    public function blog_category(): string
    {
        $data = [
            'slug'           => 'blog-category',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/blog_category', $data);
    }

}