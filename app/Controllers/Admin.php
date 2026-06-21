<?php

namespace App\Controllers;

use App\Models\AllocationResourceModel;
use App\Models\AllocationStaffModel;
use App\Models\BranchMasterModel;
use App\Models\BranchModifiedHoursModel;
use App\Models\BranchOpeningHoursModel;
use App\Models\BranchUserModel;
use App\Models\BusinessContractModel;
use App\Models\BusinessContractPaymentModel;
use App\Models\BusinessCustomerModel;
use App\Models\BusinessMasterModel;
use App\Models\BusinessPaymentMethodModel;
use App\Models\BusinessShippingFeeModel;
use App\Models\BusinessTypeModel;
use App\Models\BusinessUserModel;
use App\Models\OrderMasterModel;
use App\Models\OrderPaymentModel;
use App\Models\OtternautPackageModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductMasterModel;
use App\Models\ProductVariantInventoryModel;
use App\Models\ProductVariantModel;
use App\Models\ResourceMasterModel;
use App\Models\ResourceTypeModel;
use App\Models\ServiceMasterModel;
use App\Models\ServiceStaffModel;
use App\Models\ServiceVariantModel;
use App\Models\SessionBreakDownModel;
use App\Models\SessionMasterModel;
use App\Models\UserMasterModel;
use App\Services\ImageUploadService;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
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

    public function show404(): string|ResponseInterface
    {
        $method = $this->request->getMethod();
        $method = strtolower($method);
        if ('get' == $method) {
            $session      = session();
            $lang         = $this->request->getLocale();
            $businessName = '';
            if (isset($session->business)) {
                $businessName = $session->business['business_local_names'][$lang] ?? $session->business['business_name'];
            }
            $data    = [
                'slug'         => 'not-found',
                'lang'         => $lang,
                'businessName' => $businessName
            ];
            return view('_404', $data);
        }
        return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
            ->setJSON(['success' => STATUS_RESPONSE_ERR]);
    }

    /**
     * Dashboard page
     * @return string
     */
    public function index(): string
    {
        $session   = session();
        $dashboard = [];
        if ('OWNER' == $session->user_role) {
            $businessId   = $session->business['business_id'];
            $branchModel  = new BranchMasterModel;
            $serviceModel = new ServiceVariantModel;
            $productModel = new ProductVariantModel;
            $staffModel   = new BranchUserModel;
            $branches     = $branchModel->where('business_id', $businessId)->findAll();
            $service_raw  = $serviceModel->select('service_master.id AS service_id, COUNT(service_variant.id) AS variant_count, service_master.service_name, service_master.service_local_names')
                ->join('service_master', 'service_master.id = service_variant.service_id', 'left outer')
                ->groupBy('service_variant.service_id')
                ->where('service_master.business_id', $businessId)->findAll();
            $services     = [];
            $product_raw  = $productModel->select('product_master.id AS product_id, COUNT(product_variant.id) AS variant_count, product_master.product_name, product_master.product_local_names')
                ->join('product_master', 'product_variant.product_id = product_master.id', 'left outer')
                ->groupBy('product_variant.product_id')
                ->where('product_master.business_id', $businessId)->findAll();
            $products     = [];
            foreach ($service_raw as $service) {
                $service_names                  = json_decode($service['service_local_names'], true);
                $service_name                   = $service_names[$session->lang] ?? $service['service_name'];
                $id                             = $service['service_id'] * ID_MASKED_PRIME;
                $services[$id]['service_name']  = $service_name;
                $services[$id]['variant_count'] = $service['variant_count'];
            }
            foreach ($product_raw as $product) {
                $product_names                  = json_decode($product['product_local_names'], true);
                $product_name                   = $product_names[$session->lang] ?? $product['product_name'];
                $id                             = $product['product_id'] * ID_MASKED_PRIME;
                $products[$id]['product_name']  = $product_name;
                $products[$id]['variant_count'] = $product['variant_count'];
            }
            $staff        = $staffModel->getUsersByBusinessId($businessId);
            $dashboard    = [
                'setup' => [
                    'branches' => $branches,
                    'services' => $services,
                    'products' => $products,
                    'staff'    => $staff,
                ]
            ];
        }
        $data    = [
            'slug'      => 'dashboard',
            'lang'      => $this->request->getLocale(),
            'dashboard' => $dashboard,
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
        $error_message = lang('System.response-msg.error.generic');
        try {
            $session         = session();
            $userMasterModel = new UserMasterModel();
            $script_action   = $this->request->getPost('script_action');
            $available_lang  = get_available_locales();
            $error_msg       = lang('System.response-msg.error.generic');
            $upload_service  = new ImageUploadService();
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
                if (empty($user_gender)) {
                    $user_gender = 'U';
                }
                $data     = [
                    'telephone_number'   => $telephone_number,
                    'lang_code'          => $lang_code,
                    'user_gender'        => $user_gender,
                    'user_date_of_birth' => $user_date_of_birth,
                    'user_nationality'   => $user_nationality,
                    'profile_status_msg' => htmlentities($profile_status_msg),
                ];
                // make things null
                $nullable = ['telephone_number', 'user_date_of_birth', 'user_nationality', 'profile_status_msg'];
                foreach ($nullable as $field) {
                    if ('' == $data[$field]) {
                        $data[$field] = null;
                    }
                }
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
                $file = $this->request->getFile('avatar');
                if (!$file) {
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.upload-failed')
                    ]);
                }
                $result = $upload_service->uploadAndCropToWebp(
                    $file,
                    WRITEPATH . 'uploads/profile_pictures/',
                    'profile_' . preg_replace('/[^a-z0-9]/i', '', strtolower($session->user['email_address'])),
                    [800, 800],
                    [
                        'max_size'   => 1000,
                        'max_width'  => 1000,
                        'max_height' => 1000
                    ]
                );
                if (!$result['success']) {
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => implode('<br>', $result['errors'])
                    ]);
                }
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
            $error_message = $e->getMessage();
        }
        return $this->response->setJSON([
            'status'  => STATUS_RESPONSE_ERR,
            'message' => $error_message,
        ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
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
        $businessId           = $session->business['business_id'];
        $business             = $businessMasterModel->find($businessId);
        $businessTypes        = $businessTypeModel->retrieveData();
        $contracts            = $contractModel->retrieveDataByBusinessId($businessId);
        $allLanguages         = get_available_locales('long');
        $logo_file            = base_url('assets/img/logo.png');
        if (!empty($business['business_logo'])) {
            $logo_file = base_url('file/business_' . $business['business_logo']);
        }
        // DATA
        $business['business_local_names']       = json_decode($business['business_local_names'], true);
        $business['mart_meta_description']      = json_decode($business['mart_meta_description'], true);
        $business['mart_meta_keywords']         = json_decode($business['mart_meta_keywords'], true);
        $business['mart_store_intro_paragraph'] = json_decode($business['mart_store_intro_paragraph'], true);
        $business['social_media']               = json_decode($business['social_media'], true);
        $data = [
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
            $businessId          = $session->business['business_id'];
            $businessMasterModel = new BusinessMasterModel();
            $script_action       = $this->request->getPost('script_action');
            $available_lang      = get_available_locales();
            $social_media        = get_social_media();
            $error_msg           = lang('System.response-msg.error.generic');
            $upload_service      = new ImageUploadService();
            if ('save_business' == $script_action) {
                $fields      = ['business_type_id', 'business_name', 'business_slug', 'allow_advance_booking', 'tax_percentage', 'tax_inclusive', 'live_status', 'mart_primary_color', 'mart_text_color', 'mart_background_color', 'currency_code', 'contact_email_address', 'contact_phone_number', 'contact_website', 'shipping_options', 'shipping_fee_taxable'];
                $data        = [];
                foreach ($available_lang as $code => $language_name) {
                    $fields[] = 'business_local_names_' . $code;
                    $fields[] = 'mart_meta_description_' . $code;
                    $fields[] = 'mart_meta_keywords_' . $code;
                    $fields[] = 'mart_store_intro_paragraph_' . $code;
                }
                foreach ($social_media as $code => $social_name) {
                    $fields[] = 'social_media_' . $code;
                }
                foreach ($fields as $field) {
                    $data[$field]     = $this->request->getPost($field);
                    if (in_array($field, ['mart_primary_color', 'mart_text_color', 'mart_background_color'])) {
                        $data[$field] = str_replace('#', '', $data[$field]);
                    }
                }
                // Fix JSON field
                $business_local_names_values       = [];
                $mart_meta_description_values      = [];
                $mart_meta_keywords_values         = [];
                $mart_store_intro_paragraph_values = [];
                $social_medias_values              = [];
                foreach ($available_lang as $code => $language_name) {
                    $business_local_names_values[$code]       = $data['business_local_names_' . $code];
                    $mart_meta_description_values[$code]      = $data['mart_meta_description_' . $code];
                    $mart_meta_keywords_values[$code]         = $data['mart_meta_keywords_' . $code];
                    $mart_store_intro_paragraph_values[$code] = $data['mart_store_intro_paragraph_' . $code];
                    unset($data['business_local_names_' . $code]);
                    unset($data['mart_meta_description_' . $code]);
                    unset($data['mart_meta_keywords_' . $code]);
                    unset($data['mart_store_intro_paragraph_' . $code]);
                }
                foreach ($social_media as $code => $social_name) {
                    $social_medias_values[$code] = $data['social_media_' . $code];
                    unset($data['social_media_' . $code]);
                }
                $data['business_local_names']       = json_encode($business_local_names_values, JSON_UNESCAPED_UNICODE);
                $data['mart_meta_description']      = json_encode($mart_meta_description_values, JSON_UNESCAPED_UNICODE);
                $data['mart_meta_keywords']         = json_encode($mart_meta_keywords_values, JSON_UNESCAPED_UNICODE);
                $data['mart_store_intro_paragraph'] = json_encode($mart_store_intro_paragraph_values, JSON_UNESCAPED_UNICODE);
                $data['social_media']               = json_encode($social_medias_values, JSON_UNESCAPED_UNICODE);
                // Save
                if ($businessMasterModel->update($businessId, $data)) {
                    // Reset business session
                    $businessUserModel = new BusinessUserModel();
                    $businesses        = $businessUserModel->getBusinessesByUserId($session->user_id, true, $data['business_slug']);
                    $currentBusiness   = $businesses[0];
                    $session->set('business', $currentBusiness);
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved')
                    ]);
                }
            } else if ('upload_logo' == $script_action) {
                $file = $this->request->getFile('logo');
                if (!$file) {
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.upload-failed')
                    ]);
                }
                $result = $upload_service->uploadAndCropToWebp(
                    $file,
                    WRITEPATH . 'uploads/business_logos/',
                    'logo_' . $session->business['business_slug'],
                    [1000, 1000],
                    [
                        'max_size'   => 1000,
                        'max_width'  => 1200,
                        'max_height' => 1200
                    ]
                );
                if (!$result['success']) {
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => implode('<br>', $result['errors'])
                    ]);
                }
                $session->set('business_logo', base_url('file/business_' . $result['file_name']));
                $businessMasterModel->update($businessId, ['business_logo' => $result['file_name']]);
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
            } else if ('upload_header_img' == $script_action) {
                $business_slug = $session->business['business_slug'];
                $file = $this->request->getFile('header-img');
                if (!$file) {
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.upload-failed')
                    ]);
                }
                $result = $upload_service->uploadAndCropToWebp(
                    $file,
                    WRITEPATH . 'uploads/business_header_images/',
                    'header_' . $business_slug,
                    [1200, 800],
                    [
                        'max_size'   => 1500,
                        'max_width'  => 2500,
                        'max_height' => 1500
                    ]
                );
                if (!$result['success']) {
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => implode('<br>', $result['errors'])
                    ]);
                }
                $session->set('business_header', base_url('file/business_' . $result['file_name']));
                $businessMasterModel->update($businessId, ['business_header' => $result['file_name']]);
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.uploaded')
                ]);
            } else if ('remove_header_img' == $script_action) {
                $business      = $businessMasterModel->find($businessId);
                $file_name     = $business['business_header'];
                $file_path     = WRITEPATH . 'uploads/business_header_images/' . $file_name;
                if (!empty($file_name) && file_exists($file_path)) {
                    if (unlink($file_path)) {
                        // Update database & session
                        $session->set('business_header', '');
                        $businessMasterModel->update($businessId, ['business_header' => null]);
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
        $businessId            = $session->business['business_id'];
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
            $data['business_id']      = $session->business['business_id'];
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
                ->where('business_id', $session->business['business_id'])
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
                $hours[$hour['day_of_the_week']] = [$hour['id'], substr($hour['opening_hours'], 0, 5), substr($hour['closing_hours'], 0, 5)];
            }
            // FIX MODE
            $mode      = 'edit';
        }
        // OPTIONS
        $subdivisions = get_country_subdivisions($session->business['country_code']);
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

    public function business_branch_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $businessId = $session->business['business_id'];
            $table = $this->request->getPost('action_table');
            $data = [];
            if ('branch_master' == $table) {
                $bmModel = new BranchMasterModel();
                $fields = [
                    'id', 'subdivision_code', 'branch_name', 'branch_slug', 'timezone_code', 'branch_type',
                    'branch_address', 'branch_postal_code', 'google_map_url', 'branch_status'
                ];
                foreach ($fields as $field) {
                    $data[$field] = $this->request->getPost($field);
                }
                $locales = get_available_locales();
                $raw_data = [];
                foreach ($locales as $locale_code => $locale_name) {
                    $field = 'branch_local_names_' . $locale_code;
                    $raw_data[$locale_code] = $this->request->getPost($field);
                }
                $data['branch_local_names'] = json_encode($raw_data, JSON_UNESCAPED_UNICODE);
                $data['business_id'] = $businessId;
                $branchId = $data['id'];
                unset($data['id']);
                // insert or update
                if (0 < $branchId) {
                    if ($bmModel->update($branchId, $data)) {
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.data-saved'),
                        ]);
                    }
                } else {
                    if ($bmModel->insert($data)) {
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.data-saved'),
                        ]);
                    }
                }
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_ERR,
                    'message' => lang('System.response-msg.error.db-issue')
                ]);
            } else if ('branch_opening_hours' == $table) {
                $hoursModel = new BranchOpeningHoursModel();
                $fields = ['branch_opening_hours_id', 'branch_id', 'day_of_the_week', 'opening_hours', 'closing_hours'];
                foreach ($fields as $field) {
                    $data[$field] = $this->request->getPost($field);
                }
                $id = $data['branch_opening_hours_id'];
                unset($data['branch_opening_hours_id']);
                // insert or update
                if (0 < $id) {
                    if ($data['opening_hours'] == '00:00' && $data['closing_hours'] == '00:00') {
                        if ($hoursModel->delete($id)) {
                            return $this->response->setJSON([
                                'status'  => STATUS_RESPONSE_OK,
                                'message' => lang('System.response-msg.success.data-deleted'),
                            ]);
                        }
                    } else if ($hoursModel->update($id, $data)) {
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.data-saved'),
                        ]);
                    }
                } else {
                    if ($hoursModel->insert($data)) {
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.data-saved'),
                        ]);
                    }
                }
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_ERR,
                    'message' => lang('System.response-msg.error.db-issue')
                ]);
            } else if ('branch_modified_hours' == $table) {
                $action     = $this->request->getPost('action_perform');
                $hoursModel = new BranchModifiedHoursModel();
                if ('delete' == $action) {
                    $id     = $this->request->getPost('id');
                    if ($hoursModel->delete($id)) {
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.data-deleted'),
                        ]);
                    }
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.db-issue')
                    ]);
                } else {
                    $fields     = ['branch_id', 'modified_hours_date', 'modified_reason', 'modified_type', 'updated_opening_hours', 'updated_closing_hours'];
                    foreach ($fields as $field) {
                        $data[$field] = $this->request->getPost($field);
                    }
                    if ($hoursModel->insert($data)) {
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.data-saved'),
                        ]);
                    }
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.db-issue')
                    ]);
                }
            }
            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        $businessId    = $session->business['business_id'];
        $userId        = (int) $userId / ID_MASKED_PRIME;
        $userModel     = new UserMasterModel();
        $businessModel = new BusinessUserModel();
        $branchModel   = new BranchMasterModel();
        $buModel       = new BranchUserModel();
        $mode          = 'new';
        $user          = [];
        $businessUser  = [];
        $branchUser    = [];
        $branches      = [];
        if (0 < $userId) {
            $mode         = 'edit';
            $user         = $userModel->find($userId);
            if (!empty($user)) {
                $businessUser = $businessModel->where('user_id', $userId)->where('business_id', $businessId)->first();
                $branchUser   = $buModel->getUserByBusinessId($userId, $businessId);
                $branchesRaw  = $branchModel->where('business_id', $businessId)->findAll();
                foreach ($branchesRaw as $branch) {
                    $local_names             = json_decode($branch['branch_local_names'], true);
                    $branches[$branch['id']] = $local_names[$session->lang] ?? $branch['branch_name'];
                }
            } else {
                throw new PageNotFoundException(lang('Admin.pages.page-not-found'));
            }
        }
        $data = [
            'slug'         => 'business-user-manage',
            'lang'         => $this->request->getLocale(),
            'mode'         => $mode,
            'user'         => $user,
            'userIdUrl'    => $userId * ID_MASKED_PRIME,
            'businessUser' => $businessUser,
            'branchUser'   => $branchUser,
            'branches'     => $branches,
            'breadcrumb'   => [
                [
                    'url'        => base_url('admin/business/user'),
                    'page_title' => lang('Admin.pages.business-user'),
                ]
            ]
        ];
        return view('admin/business_user_management', $data);
    }

    public function business_user_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $action = $this->request->getPost('action');
            $id     = $this->request->getPost('id');
            if ('user_master' === $action) {
                $uModel  = new UserMasterModel();
                $buModel = new BusinessUserModel();
                $fields  = ['email_address', 'user_name_first', 'user_name_last', 'user_public_name', 'account_status'];
                $data    = [];
                foreach ($fields as $field) {
                    $data[$field] = $this->request->getPost($field);
                }
                if (empty($data['user_public_name'])) {
                    $data['user_public_name'] = $data['user_name_first'];
                }
                if (0 < $id) {
                    if ($uModel->update($id, $data)) {
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.data-saved'),
                        ]);
                    }
                } else {
                    $db = \Config\Database::connect();
                    $db->transBegin(); // <<< START TRANSACTION
                    $data['account_status']  = 'P';
                    $data['user_gender']     = 'U';
                    $data['user_type']       = 'CLIENT';
                    $data['lang_code']       = $session->lang;
                    $password                = generate_secure_password(16, true);
                    $data['password_hash']   = $uModel->hash_password($password);
                    $data['password_expiry'] = date(DATE_FORMAT_DB, strtotime('-1 day'));
                    $uModel->insert($data);
                    $userId  = $uModel->getInsertID();
                    $bu_data = [
                        'business_id'         => $session->business['business_id'],
                        'user_id'             => $userId,
                        'user_role'           => 'STAFF',
                        'role_status'         => 'ACTIVE',
                        'my_default_business' => 'Y'
                    ];
                    $buModel->insert($bu_data);
                    if ($db->transStatus() === false) {
                        $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_ERR,
                            'message' => lang('System.response-msg.error.db-issue') . ' [DBI]'
                        ]);
                    }
                    // EMAIL
                    $exp       = dechex(strtotime('+20 minutes')*11);
                    $userTkn   = dechex($userId*37);
                    $hash      = substr(hash('sha256', $data['email_address']), 0, 15);
                    $token     = "$exp-$userTkn-$hash";
                    $tknLnk    = base_url('account-activation?hl=' . $session->lang . '&token=' . $token);
                    $subject   = lang('System.email.new-user.subject');
                    $message   = lang('System.email.new-user.message', [$tknLnk, $data['email_address'], $password]);
                    $preheader = substr($message, 0, 50);
                    $reply_to  = getenv('SUPPORT_EMAIL');
                    log_message('debug', 'EMAIL: SUBJECT: ' . $subject);
                    log_message('debug', 'EMAIL: MESSAGE: ' . $message);
                    if (!send_system_email($data['email_address'], $subject, $preheader, $message, $reply_to)) {
                        $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_ERR,
                            'message' => lang('System.response-msg.error.account-created-issue') . ' [EMF]'
                        ]);
                    }
                    $db->transCommit();
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else if ('business_user' == $action) {
                $buModel  = new BusinessUserModel();
                $fields = ['business_user_id', 'user_role', 'role_status'];
                $data   = [];
                foreach ($fields as $field) {
                    $data[$field] = $this->request->getPost($field);
                }
                $id = $data['business_user_id'];
                unset($data['business_user_id']);
                if ($buModel->update($id, $data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else if ('branch_user_add' == $action) {
                $bruModel  = new BranchUserModel();
                $fields    = ['id', 'branch_user_role', 'branch_id'];
                $data      = [];
                foreach ($fields as $field) {
                    $data[$field] = $this->request->getPost($field);
                }
                $data['user_id']   = $data['id'];
                $data['user_role'] = $data['branch_user_role'];
                unset($data['id']);
                unset($data['branch_user_role']);
                if ($bruModel->insert($data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else if ('branch_user_update' == $action) {
                $bruModel          = new BranchUserModel();
                $data['user_role'] = $this->request->getPost('user_role');
                $id                = $this->request->getPost('id');
                if ($bruModel->update($id, $data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else if ('branch_user_delete' == $action) {
                $bruModel  = new BranchUserModel();
                $id        = $this->request->getPost('id');
                if ($bruModel->delete($id)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-deleted'),
                    ]);
                }
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.db-issue'),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
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

    public function business_payment_method(): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER'])) {
            return $this->forbiddenResponse('string');
        }
        $paymentModel    = new BusinessPaymentMethodModel();
        $results         = $paymentModel->get_methods_for_business();
        $session         = session();
        $countryCode     = $session->business['country_code'];
        $availableMethod = [];
        if ('TH' == $countryCode) {
            $availableMethod = [
                'cash',
                'bank_transfer',
                'promptpay_static',
                'external_online'
            ];
        }
        $data         = [
            'slug'            => 'business-payment-method',
            'lang'            => $this->request->getLocale(),
            'countryCode'     => $countryCode,
            'results'         => $results,
            'availableMethod' => $availableMethod,
        ];
        return view('admin/business_payment_method', $data);
    }

    /**
     * @throws \ReflectionException
     */
    public function business_payment_method_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        $paymentModel   = new BusinessPaymentMethodModel();
        $payment_method = $this->request->getPost('payment_method');
        $data           = [];
        $cache          = \CodeIgniter\Config\Services::cache();
        if ('remove-payment-method' == $payment_method) {
            $payment_id  = $this->request->getPost('payment_id');
            $business_id = $session->business['business_id'];
            $check       = $paymentModel->where('id', $payment_id)->where('business_id', $business_id)->first();
            if ($check) {
                if ($paymentModel->delete($payment_id)) {
                    $cache_key = 'business_payment_methods-for-' . $business_id;
                    $cache->delete($cache_key);
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-deleted'),
                    ]);
                }
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.db-issue')
            ]);
        } else if ('cash' == $payment_method) {
            $availableLocale = get_available_locales();
            $fields = ['id', 'business_id'];
            foreach ($fields as $field) {
                $data[$field] = $this->request->getPost('cash_' . $field);
            }
            foreach ($availableLocale as $key => $dummy) {
                $data['payment_instruction'][$key] = $this->request->getPost('cash_payment_instruction_instruction_' . $key);
            }
            $data['payment_method']      = 'cash';
            $data['payment_instruction'] = json_encode($data['payment_instruction'], JSON_UNESCAPED_UNICODE);
        } else if ('bank_transfer' == $payment_method) {
            $fields = ['id', 'business_id'];
            foreach ($fields as $field) {
                $data[$field] = $this->request->getPost('bank_transfer_' . $field);
            }
            $instruction_fields = ['swift_code', 'account_name', 'account_number'];
            $instructions       = [];
            foreach ($instruction_fields as $field) {
                $instructions[$field] = $this->request->getPost('bank_transfer_payment_instruction_' . $field);
            }
            $data['payment_instruction'] = json_encode($instructions, JSON_UNESCAPED_UNICODE);
            $data['payment_method']      = 'bank_transfer';
        } else if ('promptpay_static' == $payment_method) {
            $fields = ['id', 'business_id'];
            foreach ($fields as $field) {
                $data[$field] = $this->request->getPost('promptpay_static_' . $field);
            }
            $instruction_fields = ['type', 'target_value'];
            $instructions       = [];
            foreach ($instruction_fields as $field) {
                $instructions[$field] = $this->request->getPost('promptpay_static_payment_instruction_' . $field);
            }
            $data['payment_instruction'] = json_encode($instructions, JSON_UNESCAPED_UNICODE);
            $data['payment_method']      = 'promptpay_static';
        } else if ('external_online' == $payment_method) {
            $fields = ['id', 'business_id'];
            foreach ($fields as $field) {
                $data[$field] = $this->request->getPost('external_online_' . $field);
            }
            $availableLocale    = get_available_locales();
            $instruction_fields = ['title', 'instruction'];
            $instructions       = [];
            foreach ($instruction_fields as $field) {
                foreach ($availableLocale as $key => $dummy) {
                    $instructions[$field][$key] = $this->request->getPost('external_online_payment_instruction_' . $field . '_' . $key);
                }
            }
            $data['payment_instruction'] = json_encode($instructions, JSON_UNESCAPED_UNICODE);
            $data['payment_method']      = 'external_online';
        }
        if (!isset($data['business_id'])) {
            $data['business_id'] = $session->business['business_id'];
        }
        $cache_key = 'business_payment_methods-for-' . $data['business_id'];
        if (0 < $data['id']) {
            $cache->delete($cache_key);
            if ($paymentModel->update($data['id'], $data)) {
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.data-saved'),
                ]);
            }
        } else {
            $cache->delete($cache_key);
            if ($paymentModel->insert($data)) {
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.data-saved'),
                ]);
            }
        }
        return $this->response->setJSON([
            'status'  => STATUS_RESPONSE_ERR,
            'message' => lang('System.response-msg.error.db-issue')
        ]);
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
            'slug'           => 'business-resource-type',
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
                ->where('business_id', $session->business['business_id'])
                ->first();
            if (empty($resourceType)) {
                throw new PageNotFoundException(lang('Admin.pages.page-not-found'));
            }
            $resourceType['resource_local_names'] = json_decode($resourceType['resource_local_names'], true);
        }
        $data           = [
            'slug'         => 'business-resource-type-manage',
            'lang'         => $this->request->getLocale(),
            'resourceType' => $resourceType,
            'breadcrumb'   => [
                [
                    'url'        => base_url('admin/resource/type'),
                    'page_title' => lang('Admin.pages.business-resource-type'),
                ]
            ]
        ];
        return view('admin/resource_type_manage', $data);
    }

    public function resource_type_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $resourceTypeModel     = new ResourceTypeModel();
            $id                    = $this->request->getPost('id');
            $data['resource_type'] = $this->request->getPost('resource_type');
            $languages             = get_available_locales('short');
            $names                 = [];
            foreach ($languages as $code => $name) {
                $names[$code] = $this->request->getPost('resource_local_names_' . $code);
            }
            $data['resource_local_names'] = json_encode($names, JSON_UNESCAPED_UNICODE);
            if (0 < $id) {
                if ($resourceTypeModel->update($id, $data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else {
                $data['business_id'] = $session->business['business_id'];
                if ($resourceTypeModel->insert($data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.db-issue'),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        $resourceTypeModel = new ResourceTypeModel();
        $types             = $resourceTypeModel->where('business_id', $session->business['business_id'])->countAllResults();
        $data              = [
            'slug'      => 'business-resource',
            'lang'      => $this->request->getLocale(),
            'typeCount' => $types,
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
        $typesRaw      = $typeModel->where('business_id', $session->business['business_id'])->findAll();
        $branchRaw     = $branchModel->where('business_id', $session->business['business_id'])->findAll();
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
            'slug'       => 'business-resource-manage',
            'lang'       => $this->request->getLocale(),
            'resource'   => $resource,
            'types'      => $types,
            'branches'   => $branches,
            'breadcrumb' => [
                [
                    'url'        => base_url('admin/resource'),
                    'page_title' => lang('Admin.pages.business-resource'),
                ]
            ]
        ];
        return view('admin/resource_manage', $data);
    }

    public function resource_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $resourceModel = new ResourceMasterModel();
            $fields        = ['branch_id', 'resource_type_id', 'resource_name', 'resource_description', 'is_active'];
            $id            = $this->request->getPost('id');
            $data          = [];
            foreach ($fields as $field) {
                $data[$field] = $this->request->getPost($field);
            }
            if (0 < $id) {
                if ($resourceModel->update($id, $data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else {
                $data['business_id'] = $session->business['business_id'];
                if ($resourceModel->insert($data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.db-issue'),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function shipping_fee(): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $business = $session->business;
        $feeModel = new BusinessShippingFeeModel();
        $feeRates = $feeModel->where('business_id', $session->business['business_id'])->findAll();
        $data     = [
            'slug'     => 'business-shipping-fee',
            'lang'     => $this->request->getLocale(),
            'business' => $business,
            'rates'    => $feeRates
        ];
        return view('admin/shipping_fee', $data);
    }

    public function shipping_fee_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $rateModel = new BusinessShippingFeeModel();
            $action = $this->request->getPost('action');
            if ('insert-shipping-rate' == $action) {
                $fields    = ['price_range_from', 'price_range_to', 'shipping_rate', 'rate_comment', 'business_id'];
                $data      = [];
                foreach ($fields as $field) {
                    $data[$field] = $this->request->getPost($field);
                }
                if (empty($data['rate_comment'])) {
                    unset($data['rate_comment']);
                }
                if (empty($data['price_range_to'])) {
                    $data['price_range_to'] = -1;
                } else if (0 == ($data['price_range_to']*100%100)) {
                    $data['price_range_to'] += 0.99;
                }
                if ($rateModel->insert($data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else if ('delete-rate' == $action) {
                $id = $this->request->getPost('id');
                if ($rateModel->delete($id)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-deleted'),
                    ]);
                }
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.db-issue'),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Manage order
     * @return string
     */
    public function order(): string
    {
        $session             = session();

        $data = [
            'slug'           => 'order',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/order', $data);
    }

    public function order_post(): ResponseInterface
    {
        $draw            = $this->request->getPost('draw');
        $offset          = $this->request->getPost('start');
        $length          = $this->request->getPost('length');
        $search          = $this->request->getPost('search');
        $search          = $search['value'];
        $order           = $this->request->getPost('order');
        $orderBy         = $order[0]['column'] ?? 0;
        $orderDir        = $order[0]['dir'] ?? 'asc';
        $shippingOption  = $this->request->getPost('shipping_option') ?? '';
        $paymentMethod   = $this->request->getPost('payment_method') ?? '';
        $orderStatus     = $this->request->getPost('order_status') ?? '';
        $financialStatus = $this->request->getPost('financial_status') ?? '';
        $shippingStatus  = $this->request->getPost('shipping_status') ?? '';
        $orderModel      = new OrderMasterModel();
        $orders          = $orderModel->getDataTable($draw, $offset, $length, $search, $orderBy, $orderDir, $shippingOption, $paymentMethod, $orderStatus, $financialStatus, $shippingStatus);
        return $this->response->setJSON($orders);
    }

    public function order_info(int $orderId): string
    {
        // anyone can see the order, no restrictions
        $session           = session();
        $business          = $session->business;
        $realId            = $orderId / ID_MASKED_PRIME;
        $orderModel        = new OrderMasterModel();
        $orderPaymentModel = new OrderPaymentModel();
        $orderDetail       = $orderModel->getOrderInfo($realId);
        $data              = [
            'slug'             => 'order-info',
            'lang'             => $this->request->getLocale(),
            'breadcrumb'       => [
                [
                    'url'        => base_url('admin/order'),
                    'page_title' => lang('Admin.pages.order'),
                ]
            ],
            'order_id'         => $realId,
            'order_detail'     => $orderDetail,
            'business'         => $business,
            'statuses'         => $orderModel->getStatusIcons(),
            'payment_statuses' => $orderPaymentModel->getStatusIcons()
        ];
        return view('admin/order_info', $data);
    }

    public function order_info_post(): ResponseInterface
    {
        // anyone can see the order, no restrictions
        return $this->response->setJSON([]);
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
     * Manage staff allocation
     * @return string
     */
    public function allocation_by_service(): string
    {
        $data = [
            'slug'           => 'allocation-by-service',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/allocation_by_service', $data);
    }

    /**
     * Manage service
     * @return string
     */
    public function service(): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $data         = [
            'slug'     => 'service',
            'lang'     => $this->request->getLocale()
        ];
        return view('admin/service', $data);
    }

    /**
     * Manage service
     * @return ResponseInterface
     */
    public function service_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        $serviceModel = new ServiceMasterModel();
        $raw          = $serviceModel->getServicesForBusiness($session->business['business_id']);
        $final        = [];
        foreach ($raw as $service) {
            $final[] = [
                $service['service_local_names'][$session->lang] ?? $service['service_name'],
                lang('ServiceMaster.enum.is_active.' . $service['is_active']),
                '<a class="btn btn-primary btn-sm float-end" href="' . base_url('admin/service/' . ($service['id'] * ID_MASKED_PRIME)) . '"> ' . lang('System.buttons.edit') . '</a>'
            ];
        }
        return $this->response->setJSON([
            'data' => $final
        ]);
    }

    /**
     * @param int $serviceId
     * @return string
     */
    public function service_manage(int $serviceId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $serviceId       = $serviceId / ID_MASKED_PRIME;
        $serviceModel    = new ServiceMasterModel();
        $variantModel    = new ServiceVariantModel();
        $staffModel      = new ServiceStaffModel();
        $branchModel     = new BranchUserModel();
        $service         = [];
        $variants        = [];
        $staff           = [];
        $staffFinalList  = [];
        $mode            = 'new';
        if (0 < $serviceId) {
            $service                        = $serviceModel->findRow($serviceId);
            $service['service_local_names'] = json_decode($service['service_local_names'], true);
            $service['service_description'] = json_decode($service['service_description'], true);
            $variants                       = $variantModel->getVariantsForService($serviceId);
            $staff                          = $staffModel->getStaffByServiceId($serviceId);
            $mode                           = 'edit';
            $staffList                      = $branchModel->getUsersByBusinessId($session->business['business_id']);
            foreach ($staffList as $row) {
                $row['branch_local_names']  = json_decode($row['branch_local_names'], true);
                $branch_name                = $row['branch_local_names'][$session->lang] ?? $row['branch_name'];
                $staffFinalList[$row['id']] = $row['user_name_first'] . ' ' . $row['user_name_last'] . ' - ' . $branch_name;
            }
        }
        $data         = [
            'slug'       => 'service-manage',
            'lang'       => $this->request->getLocale(),
            'breadcrumb' => [
                [
                    'url'        => base_url('admin/service'),
                    'page_title' => lang('Admin.pages.service'),
                ]
            ],
            'mode'       => $mode,
            'service'    => $service,
            'variants'   => $variants,
            'staff'      => $staff,
            'staffList'  => $staffFinalList,
        ];
        return view('admin/service_manage', $data);
    }

    public function service_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $serviceModel  = new ServiceMasterModel();
            $script_action = $this->request->getPost('script_action');
            if ('upload_image' == $script_action) {
                $upload_service = new ImageUploadService();
                $slug           = $this->request->getPost('slug_for_image');
                $serviceId      = $this->request->getPost('id_for_image');
                $file           = $this->request->getFile('service_image');
                if (!$file) {
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.upload-failed')
                    ]);
                }
                $result = $upload_service->uploadAndCropToWebp(
                    $file,
                    WRITEPATH . 'uploads/service_image/',
                    'service_image_' . $slug,
                    [1000, 1000],
                    [
                        'max_size'   => 1500,
                        'max_width'  => 1500,
                        'max_height' => 1500
                    ]
                );
                if (!$result['success']) {
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => implode('<br>', $result['errors'])
                    ]);
                }
                $serviceModel->update($serviceId, ['service_image' => $result['file_name']]);
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.uploaded')
                ]);
            } else if ('remove_image' == $script_action) {
                $file_name     = $this->request->getPost('service_image');
                $file_path     = WRITEPATH . 'uploads/' . $file_name;
                if (!empty($file_name) && file_exists($file_path)) {
                    if (unlink($file_path)) {
                        // Update database & session
                        $serviceId = $this->request->getPost('id_for_image');
                        $serviceModel->update($serviceId, ['service_image' => null]);
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.removed')
                        ]);
                    }
                }
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_ERR,
                    'message' => lang('System.response-msg.error.removed'),
                ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
            // SAVE OTHER DATA
            $locales      = get_available_locales();
            $id           = $this->request->getPost('service_id');
            $data         = [];
            $names        = [];
            $descriptions = [];
            $fields       = ['service_name', 'is_active'];
            foreach ($fields as $field) {
                $data[$field] = $this->request->getPost($field);
            }
            foreach ($locales as $code => $language_name) {
                $names[$code]        = $this->request->getPost('service_local_names_' . $code);
                $descriptions[$code] = $this->request->getPost('service_description_' . $code);
            }
            $data['service_local_names'] = json_encode($names, JSON_UNESCAPED_UNICODE);
            $data['service_description'] = json_encode($descriptions, JSON_UNESCAPED_UNICODE);
            if (0 < $id) {
                if ($serviceModel->update($id, $data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'id'      => $id,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else {
                $cache    = Services::cache();
                $cacheKey = 'services_for_business_id-' . $session->business['business_id'];
                if ($cache->get($cacheKey)) {
                    $cache->delete($cacheKey);
                }
                $data['business_id']          = $session->business['business_id'];
                $data['service_slug']         = generate_slug($data['service_name']);
                $data['price_active_lowest']  = 0;
                $data['price_compare_lowest'] = 0;
                if ($serviceModel->insert($data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'id'      => $serviceModel->getInsertID(),
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.db-issue'),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function service_user_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $action     = $this->request->getPost('action');
            $staffModel = new ServiceStaffModel();
            if ('add' === $action) {
                $fields = ['branch_user_id', 'service_id', 'action'];
                $data   = [];
                foreach ($fields as $field) {
                    $data[$field] = $this->request->getPost($field);
                }
                if ($staffModel->insert($data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else if ('remove' == $action) {
                $id = $this->request->getPost('id');
                if ($staffModel->delete($id)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-deleted'),
                    ]);
                }
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.db-issue'),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $serviceId
     * @param int $serviceVariantId
     * @return string
     */
    public function service_variant_manage(int $serviceId, int $serviceVariantId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $serviceId        = $serviceId / ID_MASKED_PRIME;
        $serviceVariantId = $serviceVariantId / ID_MASKED_PRIME;
        $serviceModel     = new ServiceMasterModel();
        $variantModel     = new ServiceVariantModel();
        $resourceModel    = new ResourceTypeModel();
        $service          = $serviceModel->findRow($serviceId);
        if (empty($service)) {
            throw PageNotFoundException::forPageNotFound();
        }
        $service['service_local_names'] = json_decode($service['service_local_names'], true);
        $resourceTypesRaw               = $resourceModel->where('business_id', $session->business['business_id'])->findAll();
        $resourceTypes                  = [];
        foreach ($resourceTypesRaw as $row) {
            $row['resource_local_names'] = json_decode($row['resource_local_names'], true);
            $resourceTypes[$row['id']]   = $row['resource_local_names'][$session->lang] ?? $row['resource_name'];
        }
        $variant                        = [];
        $mode                           = 'new';
        if (0 < $serviceVariantId) {
            $mode    = 'edit';
            $variant = $variantModel->findRow($serviceVariantId);
            if (empty($variant)) {
                throw PageNotFoundException::forPageNotFound();
            }
            $variant['variant_local_names'] = json_decode($variant['variant_local_names'], true);
        }
        $data         = [
            'slug'          => 'service-variant-manage',
            'lang'          => $this->request->getLocale(),
            'breadcrumb'    => [
                [
                    'url'        => base_url('admin/service'),
                    'page_title' => lang('Admin.pages.service'),
                ],
                [
                    'url'        => base_url('admin/service/' . ($serviceId * ID_MASKED_PRIME)),
                    'page_title' => lang('Admin.pages.service-manage'),
                ]
            ],
            'mode'          => $mode,
            'service'       => $service,
            'variant'       => $variant,
            'resourceTypes' => $resourceTypes,
        ];
        return view('admin/service_variant', $data);
    }

    public function service_variant_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $serviceModel = new ServiceMasterModel();
            $variantModel = new ServiceVariantModel();
            $cache        = \CodeIgniter\Config\Services::cache();
            $locales      = get_available_locales();
            $id           = $this->request->getPost('id');
            $serviceId    = $this->request->getPost('service_id');
            $cacheKey     = 'variants_for_service_id-' . $serviceId;
            $data         = [];
            $names        = [];
            $fields       = ['variant_name', 'is_active', 'schedule_type', 'variant_capacity', 'required_num_staff', 'service_duration_minutes', 'required_resource_type_id', 'price_active', 'price_compare'];
            foreach ($fields as $field) {
                $data[$field] = $this->request->getPost($field);
            }
            if (empty($data['required_resource_type_id'])) {
                $data['required_resource_type_id'] = null;
            }
            foreach ($locales as $code => $language_name) {
                $names[$code] = $this->request->getPost('variant_local_names_' . $code);
            }
            $data['variant_local_names'] = json_encode($names, JSON_UNESCAPED_UNICODE);
            $db = \Config\Database::connect();
            $db->transBegin(); // <<< START TRANSACTION
            if (0 < $id) {
                $variantModel->update($id, $data);
                $serviceModel->updateLowestPrices($serviceId);
                if ($cache->get($cacheKey)) {
                    $cache->delete($cacheKey);
                }
                if ($db->transStatus() === false) {
                    $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.db-issue')
                    ]);
                }
                $db->transCommit();
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.data-saved'),
                ]);
            } else {
                $data['service_id']   = $serviceId;
                $data['variant_slug'] = generate_slug($serviceId . $data['variant_name']);
                $variantModel->insert($data);
                $serviceModel->updateLowestPrices($serviceId);
                if ($cache->get($cacheKey)) {
                    $cache->delete($cacheKey);
                }
                if ($db->transStatus() === false) {
                    $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.db-issue')
                    ]);
                }
                $db->transCommit();
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.data-saved'),
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param int $serviceId
     * @param int $serviceVariantId
     * @return string
     */
    public function service_variant_session(int $serviceId, int $serviceVariantId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $businessId   = $session->business['business_id'];
        $sId          = $serviceId / ID_MASKED_PRIME;
        $svId         = $serviceVariantId / ID_MASKED_PRIME;
        $serviceModel = new ServiceMasterModel();
        $variantModel = new ServiceVariantModel();
        $branchModel  = new BranchMasterModel();
        $service      = $serviceModel->findRow($sId);
        if (empty($service)) {
            throw PageNotFoundException::forPageNotFound();
        }
        $service['service_local_names'] = json_decode($service['service_local_names'], true);
        $variant                        = $variantModel->findRow($svId);
        if (empty($variant)) {
            throw PageNotFoundException::forPageNotFound();
        }
        $lang         = $this->request->getLocale();
        $v_locales    = json_decode($variant['variant_local_names'], true);
        $title        = ($service['service_local_names'][$lang] ?? $service['service_name']) . '<br>' . ($v_locales[$lang] ?? $variant['variant_name']);
        $branches     = $branchModel->where('business_id', $businessId)->findAll();
        $all_branches = [];
        foreach ($branches as $branch) {
            $all_branches[$branch['id']] = $branch['branch_name'];
        }
        $data         = [
            'slug'          => 'service-variant-session',
            'lang'          => $lang,
            'breadcrumb'    => [
                [
                    'url'        => base_url('admin/service'),
                    'page_title' => lang('Admin.pages.service'),
                ],
                [
                    'url'        => base_url('admin/service/' . $serviceId),
                    'page_title' => lang('Admin.pages.service-manage'),
                ],
                [
                    'url'        => base_url('admin/service/variant/' . $serviceId . '/' . $serviceVariantId),
                    'page_title' => lang('Admin.pages.service-variant-manage'),
                ]
            ],
            'title'         => $title,
            'serviceIdMask' => $serviceId,
            'variantIdMask' => $serviceVariantId,
            'service'       => $service,
            'variant'       => $variant,
            'branches'      => $all_branches,
        ];
        return view('admin/service_variant_session', $data);
    }

    public function service_variant_session_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('DataTable');
        }
        $sessionMasterModel = new SessionMasterModel();
        $start              = $this->request->getPost('start');
        $length             = $this->request->getPost('length');
        $date_start         = $this->request->getPost('date_start') ?? '';
        $date_end           = $this->request->getPost('date_end') ?? '';
        $branch_id          = $this->request->getPost('branch_id');
        $draw               = $this->request->getPost('draw');
        $service_id         = $this->request->getPost('service_id');
        $service_variant_id = $this->request->getPost('service_variant_id');
        if (empty($branch_id)) {
            $branch_id = 0;
        }
        $sessions           = $sessionMasterModel->getDatatable($draw, $start, $length, $service_id, $service_variant_id, $date_start, $date_end, $branch_id);
        return $this->response->setJSON($sessions);
    }

    public function service_variant_session_manage(int $serviceId, int $serviceVariantId, int $sessionId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $lang                    = $this->request->getLocale();
        $realServiceId           = $serviceId / ID_MASKED_PRIME;
        $realServiceVariantId    = $serviceVariantId / ID_MASKED_PRIME;
        $realSessionId           = $sessionId / ID_MASKED_PRIME;
        $branchModel             = new BranchMasterModel();
        $serviceModel            = new ServiceMasterModel();
        $variantModel            = new ServiceVariantModel();
        $sessionModel            = new SessionMasterModel();
        $sessionBreakdownModel   = new SessionBreakdownModel();
        $resourceModel           = new ResourceMasterModel();
        $resourceTypeModel       = new ResourceTypeModel();
        $staffModel              = new ServiceStaffModel();
        $allocationResourceModel = new AllocationResourceModel();
        $allocationStaffModel    = new AllocationStaffModel();
        // SERVICE
        $service = $serviceModel->findRow($realServiceId);
        if (empty($service)) {
            throw PageNotFoundException::forPageNotFound();
        }
        $service['service_local_names'] = json_decode($service['service_local_names'], true);
        // VARIANT
        $variant = $variantModel->findRow($realServiceVariantId);
        if (empty($variant)) {
            throw PageNotFoundException::forPageNotFound();
        }
        $variant['variant_local_names'] = json_decode($variant['variant_local_names'], true);
        // GET DATA
        $branches      = $branchModel->where('business_id', $session->business['business_id'])->findAll();
        $branchOptions = [];
        $branchTzes      = [];
        foreach ($branches as $branch) {
            $branchNames                  = json_decode($branch['branch_local_names'], true);
            $branchOptions[$branch['id']] = $branchNames[$lang] ?? $branch['branch_name'];
            $branchTzes[$branch['id']]    = $branch['timezone_code'];
        }
        $resources = [];
        if (!empty($variant['required_resource_type_id']) && 0 < $variant['required_resource_type_id']) {
            $resources = $resourceModel->where('resource_type_id', $variant['required_resource_type_id']);
        }
        // GET SESSION ITSELF, don't get if it's the new session
        $mode                = 'new';
        $sessionData         = [];
        $sessionBreakdown    = [];
        $resourceType        = '';
        $resourceOptions     = [];
        $staffOptions        = [];
        $branchTz            = '';
        $staffAllocations    = [];
        $resourceAllocations = [];
        $lastUserId          = 0;
        $lastResourceId      = 0;
        if (0 < $realSessionId) {
            $mode        = 'edit';
            $sessionData = $sessionModel->findRow($realSessionId);
            if (empty($session)) {
                throw PageNotFoundException::forPageNotFound();
            }
            $sessionBreakdown    = $sessionBreakdownModel->where('session_id', $realSessionId)->orderBy('time_start', 'ASC')->findAll();
            $sessionBreakdownIds = [];
            foreach ($sessionBreakdown as $row) {
                $sessionBreakdownIds[] = $row['id'];
            }
            // resource
            if (!empty($variant['required_resource_type_id'])) {
                $resourceTypeRaw = $resourceTypeModel->where('id', $variant['required_resource_type_id'])->first();
                $resourceTypes   = json_encode($resourceTypeRaw['resource_local_names'], true);
                $resourceType    = $resourceTypes[$lang] ?? $resourceTypeRaw['resource_type'];
                $resourcesRaw    = $resourceModel->where('branch_id', $sessionData['branch_id'])->where('resource_type_id', $variant['required_resource_type_id'])->findAll();
                $resourceOptions = [];
                foreach ($resourcesRaw as $row) {
                    $resourceOptions[$row['id']] = $row['resource_name'];
                }
            }
            // staff
            $staffRaw        = $staffModel->getStaffByServiceId($realServiceId);
            foreach ($staffRaw as $row) {
                $staffOptions[$row['user_master_id']] = $row['user_name_first'] . ' ' . $row['user_name_last'];
            }
            // allocation
            if (!empty($sessionBreakdownIds)) {
                $staffAllocationRaw    = $allocationStaffModel->select('allocation_staff.*, user_master.user_name_first, user_master.user_name_last')
                    ->join('user_master', 'user_master.id = allocation_staff.user_id', 'left outer')
                    ->whereIn('session_breakdown_id', $sessionBreakdownIds)->findAll();
                $resourceAllocationRaw = $allocationResourceModel->select('allocation_resource.*, resource_master.resource_name')
                    ->join('resource_master', 'resource_master.id = allocation_resource.resource_id', 'left outer')
                    ->whereIn('session_breakdown_id', $sessionBreakdownIds)->findAll();
                foreach ($staffAllocationRaw as $row) {
                    $staffAllocations[$row['session_breakdown_id']] = $row['user_name_first'] . ' ' . $row['user_name_last'];
                    $lastUserId                                     = $row['user_id'];
                }
                foreach ($resourceAllocationRaw as $row) {
                    $resourceAllocations[$row['session_breakdown_id']] = $row['resource_name'];
                    $lastResourceId                                    = $row['resource_id'];
                }
            }
            // tz
            $branchTz        = $branchTzes[$sessionData['branch_id']];
        }
        $data = [
            'slug'                 => 'service-variant-session-manage',
            'lang'                 => $lang,
            'breadcrumb'           => [
                [
                    'url'        => base_url('admin/service'),
                    'page_title' => lang('Admin.pages.service'),
                ],
                [
                    'url'        => base_url('admin/service/' . $serviceId),
                    'page_title' => lang('Admin.pages.service-manage'),
                ],
                [
                    'url'        => base_url('admin/service/variant/' . $serviceId . '/' . $serviceVariantId),
                    'page_title' => lang('Admin.pages.service-variant-manage'),
                ],
                [
                    'url'        => base_url('admin/service/variant/session/' . $serviceId . '/' . $serviceVariantId),
                    'page_title' => lang('Admin.pages.service-variant-session'),
                ]
            ],
            'mode'                 => $mode,
            'service'              => $service,
            'variant'              => $variant,
            'branches'             => $branchOptions,
            'branch_tz'            => $branchTz,
            'resources'            => $resources,
            'session_data'         => $sessionData,
            'sessions_list'        => $sessionBreakdown,
            'resource_type'        => $resourceType,
            'resource_options'     => $resourceOptions,
            'staff_options'        => $staffOptions,
            'staff_allocations'    => $staffAllocations,
            'resource_allocations' => $resourceAllocations,
            'url_ids'              => $serviceId . '/' . $serviceVariantId,
            'last_user_id'         => $lastUserId,
            'last_resource_id'     => $lastResourceId,
        ];
        return view('admin/service_variant_session_manage', $data);
    }

    public function service_variant_session_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('DataTable');
        }
        $sessionMasterModel      = new SessionMasterModel();
        $sessionBreakdownModel   = new SessionBreakdownModel();
        $branchMasterModel       = new BranchMasterModel();
        $allocationResourceModel = new AllocationResourceModel();
        $allocationStaffModel    = new AllocationStaffModel();
        $data                    = [];
        $db                      = \Config\Database::connect();
        try {
            $action     = $this->request->getPost('action');
            if ('session_master' == $action) {
                $fields = ['id', 'session_type', 'service_variant_id', 'date_start', 'date_end', 'branch_id', 'session_capacity', 'short_description'];
                foreach ($fields as $field) {
                    $data[$field] = $this->request->getPost('session_master_' . $field);
                }
                $row_id = $data['id'] ?? 0;
                unset($data['id']);
                if (empty($data['date_start'])) {
                    $data['date_start'] = null;
                }
                if (empty($data['date_end'])) {
                    $data['date_end'] = null;
                }
                if (0 == $row_id) {
                    if ($sessionMasterModel->insert($data)) {
                        $id = $sessionMasterModel->getInsertID();
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.data-saved'),
                            'id'      => $id
                        ]);
                    }
                } else {
                    if ($sessionMasterModel->update($row_id, $data)) {
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.data-saved'),
                            'id'      => $row_id
                        ]);
                    }
                }
            } else if ('session_breakdown' == $action) {
                $fields           = ['date_start', 'time_start', 'date_end', 'time_end', 'resource_master_id', 'staff_user_id', 'session_master_id'];
                $sessionBreakdown = [];
                $data             = [];
                foreach ($fields as $field) {
                    $data[$field] = $this->request->getPost('session_breakdown_' . $field);
                }
                $sessionMaster = $sessionMasterModel->where('id', $data['session_master_id'])->first();
                if (empty($sessionMaster)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.db-issue'),
                    ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }
                log_message('debug', 'session master = ' . json_encode($sessionMaster));
                $branchId     = $sessionMaster['branch_id'];
                $branchMaster = $branchMasterModel->where('id', $branchId)->first();
                if (empty($branchMaster)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.db-issue'),
                    ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }
                $db->transBegin();
                log_message('debug', 'branch = ' . json_encode($branchMaster));
                $srcTz     = new \DateTimeZone($branchMaster['timezone_code']);
                $utcTz     = new \DateTimeZone('UTC');
                // session_breakdown
                $sessionBreakdown['session_id'] = $sessionMaster['id'];
                $timeStart                      = $data['date_start'] . ' ' . $data['time_start'] . ':00';
                $startObj                       = new DateTime($timeStart, $srcTz);
                $startObj->setTimezone($utcTz);
                $sessionBreakdown['time_start'] = $startObj->format('Y-m-d H:i:s');
                $timeEnd                        = $data['date_end'] . ' ' . $data['time_end'] . ':00';
                $endObj                         = new DateTime($timeEnd, $srcTz);
                $endObj->setTimezone($utcTz);
                $sessionBreakdown['time_end']   = $endObj->format('Y-m-d H:i:s');
                if ($sessionBreakdown['time_end'] < $sessionBreakdown['time_start']) {
                    $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                    log_message('debug', 'session breakdown start after end = ' . $sessionBreakdown['time_end'] . ' < ' . $sessionBreakdown['time_start']);
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.time-start-after-end'),
                    ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }
                log_message('debug', 'session breakdown - INSERTING = ' . json_encode($sessionBreakdown));
                $sessionBreakdownModel->insert($sessionBreakdown);
                log_message('debug', 'session breakdown - INSERTED');
                $breakdownId                    = $sessionBreakdownModel->getInsertID();
                // allocation
                if (0 < $data['resource_master_id']) {
                    // check conflict
                    log_message('debug', 'conflict checking = ' . $data['resource_master_id'] . ' / ' . $sessionBreakdown['time_start'] . ' / ' . $sessionBreakdown['time_end']);
                    $conflict = $allocationResourceModel->checkResourceConflict($data['resource_master_id'], $sessionBreakdown['time_start'], $sessionBreakdown['time_end']);
                    log_message('debug', 'resource conflict = ' . json_encode($conflict));
                    if (!empty($conflict)) {
                        log_message('debug', 'conflict detected on resource!');
                        $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_ERR,
                            'message' => lang('System.response-msg.error.time-conflict-resource'),
                            'data'    => $conflict
                        ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                    }
                    $allocationResourceData['resource_id']          = $data['resource_master_id'];
                    $allocationResourceData['session_breakdown_id'] = $breakdownId;
                    $allocationResourceData['allocation_type']      = 'SESSION';
                    log_message('debug', 'allocationResourceData = ' . json_encode($allocationResourceData));
                    $allocationResourceModel->insert($allocationResourceData);
                }
                if (0 < $data['staff_user_id']) {
                    // check conflict
                    log_message('debug', 'conflict checking = ' . $data['staff_user_id'] . ' / ' . $sessionBreakdown['time_start'] . ' / ' . $sessionBreakdown['time_end']);
                    $conflict = $allocationStaffModel->checkStaffConflict($data['staff_user_id'], $sessionBreakdown['time_start'], $sessionBreakdown['time_end']);
                    log_message('debug', 'user conflict = ' . json_encode($conflict));
                    if (!empty($conflict)) {
                        log_message('debug', 'conflict detected on staff!');
                        $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_ERR,
                            'message' => lang('System.response-msg.error.time-conflict-staff'),
                            'data'    => $conflict
                        ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                    }
                    $allocationStaffData['user_id']              = $data['staff_user_id'];
                    $allocationStaffData['session_breakdown_id'] = $breakdownId;
                    $allocationStaffData['allocation_type']      = 'SESSION';
                    log_message('debug', 'allocationStaffData = ' . json_encode($allocationStaffData));
                    $allocationStaffModel->insert($allocationStaffData);
                }
                // update session_master
                $existingBreakdown          = $sessionBreakdownModel->where('session_id', $sessionMaster['id'])->findAll();
                $masterUpdate               = [];
                $masterUpdate['date_start'] = $data['date_start'];
                $masterUpdate['date_end']   = $data['date_end'];
                log_message('debug', 'masterUpdate = ' . json_encode($masterUpdate));
                if (!empty($existingBreakdown)) {
                    foreach ($existingBreakdown as $row) {
                        $objStart = new DateTime($row['time_start'], $utcTz);
                        $objStart->setTimezone($srcTz);
                        $dtStart  = $objStart->format('Y-m-d');
                        $objEnd   = new DateTime($row['time_end'], $utcTz);
                        $objEnd->setTimezone($srcTz);
                        $dtEnd    = $objEnd->format('Y-m-d');
                        if ($masterUpdate['date_start'] > $dtStart) {
                            $masterUpdate['date_start'] = $dtStart;
                        }
                        if ($masterUpdate['date_end'] < $dtEnd) {
                            $masterUpdate['date_end'] = $dtEnd;
                        }
                        log_message('debug', 'masterUpdate (loop) = ' . json_encode($masterUpdate));
                    }
                }
                $sessionMasterModel->update($sessionMaster['id'], $masterUpdate);
                log_message('debug', 'session master - UPDATED');
                if ($db->transStatus() === false) {
                    log_message('debug', 'session breakdown insert - FAILED UPDATE - ROLLBACK');
                    $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.db-issue'),
                    ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }
                $db->transCommit();
                log_message('debug', 'session master - COMMITTED');
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.data-saved')
                ]);
            } else if ('remove_session_breakdown' == $action) {
                $session_breakdown_id = $this->request->getPost('session_breakdown_id');
                $db->transBegin();
                $alStaff = $allocationStaffModel->where('session_breakdown_id', $session_breakdown_id)->findAll();
                if (!empty($alStaff)) {
                    foreach ($alStaff as $row) {
                        $allocationStaffModel->delete($row['id']);
                    }
                }
                $alResource = $allocationResourceModel->where('session_breakdown_id', $session_breakdown_id)->findAll();
                if (!empty($alResource)) {
                    foreach ($alResource as $row) {
                        $allocationResourceModel->delete($row['id']);
                    }
                }
                $sessionBreakdownModel->delete($session_breakdown_id);
                if ($db->transStatus() === false) {
                    $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.db-issue'),
                    ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }
                $db->transCommit();
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.data-deleted')
                ]);
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.db-issue'),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Manage product
     * @return string
     */
    public function product(): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $categoryModel = new ProductCategoryModel();
        $count         = $categoryModel->where('business_id', $session->business['business_id'])->countAllResults();
        $data          = [
            'slug'           => 'product',
            'lang'           => $this->request->getLocale(),
            'count'          => $count,
        ];
        return view('admin/product', $data);
    }

    /**
     * Manage product
     * @return ResponseInterface
     */
    public function product_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        $productModel = new ProductMasterModel();
        return $this->response->setJSON([
            'data' => $productModel->getDataTable($session->business['business_id'])
        ]);
    }

    public function product_manage(int $productId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $productId     = $productId / ID_MASKED_PRIME;
        $productModel  = new ProductMasterModel();
        $categoryModel = new ProductCategoryModel();
        $variantModel  = new ProductVariantModel();
        $product       = [];
        $cateRaw       = $categoryModel->where('business_id', $session->business['business_id'])->findAll();
        $categories    = [];
        $variants      = [];
        foreach ($cateRaw as $row) {
            $local_names            = json_decode($row['category_local_names'], true);
            $categories[$row['id']] = $local_names[$session->lang] ?? $row['category_name'];
        }
        $mode          = 'new';
        if (0 < $productId) {
            $mode    = 'edit';
            $product = $productModel->findRow($productId);
            if (empty($product)) {
                throw PageNotFoundException::forPageNotFound();
            }
            $product['product_local_names'] = json_decode($product['product_local_names'], true);
            $product['product_description'] = json_decode($product['product_description'], true);
            $variants                       = $variantModel->where('product_id', $productId)->findAll();
        }
        $data = [
            'slug'       => 'product-manage',
            'lang'       => $this->request->getLocale(),
            'breadcrumb' => [
                [
                    'url'        => base_url('admin/product'),
                    'page_title' => lang('Admin.pages.product'),
                ]
            ],
            'product'    => $product,
            'categories' => $categories,
            'variants'   => $variants,
            'mode'       => $mode,
        ];
        return view('admin/product_manage', $data);
    }

    public function product_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $productModel = new ProductMasterModel();
            $script_action = $this->request->getPost('script_action');
            if ('upload_image' == $script_action) {
                $upload_service = new ImageUploadService();
                $slug           = $this->request->getPost('slug_for_image');
                $productId      = $this->request->getPost('id_for_image');
                $file           = $this->request->getFile('product_image');
                if (!$file) {
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.upload-failed')
                    ]);
                }
                $result = $upload_service->uploadAndCropToWebp(
                    $file,
                    WRITEPATH . 'uploads/product_image/',
                    'product_image_' . $slug,
                    [1000, 1000],
                    [
                        'max_size'   => 1500,
                        'max_width'  => 1500,
                        'max_height' => 1500
                    ]
                );
                if (!$result['success']) {
                    return $this->response->setJSON([
                        'success' => STATUS_RESPONSE_ERR,
                        'message' => implode('<br>', $result['errors'])
                    ]);
                }
                $productModel->update($productId, ['product_image' => $result['file_name']]);
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'message' => lang('System.response-msg.success.uploaded')
                ]);
            } else if ('remove_image' == $script_action) {
                $file_name     = $this->request->getPost('product_image');
                $file_path     = WRITEPATH . 'uploads/' . $file_name;
                if (!empty($file_name) && file_exists($file_path)) {
                    if (unlink($file_path)) {
                        // Update database & session
                        $productId = $this->request->getPost('id_for_image');
                        $productModel->update($productId, ['product_image' => null]);
                        return $this->response->setJSON([
                            'status'  => STATUS_RESPONSE_OK,
                            'message' => lang('System.response-msg.success.removed')
                        ]);
                    }
                }
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_ERR,
                    'message' => lang('System.response-msg.error.removed'),
                ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
            // SAVE OTHER DATA
            $locales      = get_available_locales();
            $id           = $this->request->getPost('product_id');
            $data         = [];
            $names        = [];
            $descriptions = [];
            $fields       = ['product_category_id', 'product_name', 'product_tag', 'product_type', 'is_active'];
            foreach ($fields as $field) {
                $data[$field] = $this->request->getPost($field);
            }
            foreach ($locales as $code => $language_name) {
                $names[$code]        = $this->request->getPost('product_local_names_' . $code);
                $descriptions[$code] = $this->request->getPost('product_description_' . $code);
            }
            $data['product_local_names'] = json_encode($names, JSON_UNESCAPED_UNICODE);
            $data['product_description'] = json_encode($descriptions, JSON_UNESCAPED_UNICODE);
            if (0 < $id) {
                if ($productModel->update($id, $data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'id'      => $id,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else {
                $data['business_id']          = $session->business['business_id'];
                $data['product_slug']         = generate_slug($data['product_name']);
                $data['price_active_lowest']  = 0;
                $data['price_compare_lowest'] = 0;
                if ($productModel->insert($data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'id'      => $productModel->getInsertID(),
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.db-issue'),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function product_variant_manage(int $productId, int $variantId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $productLink  = base_url('admin/product/' . $productId);
        $variantId    = $variantId / ID_MASKED_PRIME;
        $variantModel = new ProductVariantModel();
        $variant      = [];
        $mode         = 'new';
        if (0 < $variantId) {
            $variant = $variantModel->getVariantInformation($variantId);
            if (empty($variant)) {
                throw PageNotFoundException::forPageNotFound();
            }
            $mode    = 'edit';
        }
        $data = [
            'slug'       => 'product-variant-manage',
            'lang'       => $this->request->getLocale(),
            'breadcrumb' => [
                [
                    'url'        => base_url('admin/product'),
                    'page_title' => lang('Admin.pages.product'),
                ],
                [
                    'url'        => $productLink,
                    'page_title' => lang('Admin.pages.product-manage'),
                ]
            ],
            'pIdPrime'   => $productId,
            'productId'  => $productId / ID_MASKED_PRIME,
            'variant'    => $variant,
            'mode'       => $mode,
        ];
        return view('admin/product_variant', $data);
    }

    public function product_variant_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $productModel   = new ProductMasterModel();
            $variantModel   = new ProductVariantModel();
            $inventoryModel = new ProductVariantInventoryModel();
            $locales        = get_available_locales();
            $id             = $this->request->getPost('variant_id');
            $data           = [];
            $names          = [];
            $fields         = ['product_id', 'variant_name', 'variant_sku', 'is_active', 'inventory_count', 'price_active', 'price_compare'];
            foreach ($fields as $field) {
                $data[$field] = $this->request->getPost($field);
            }
            foreach ($locales as $code => $language_name) {
                $names[$code] = $this->request->getPost('variant_local_names_' . $code);
            }
            $data['variant_local_names'] = json_encode($names, JSON_UNESCAPED_UNICODE);
            $db             = \Config\Database::connect();
            if (0 < $id) {
                $db->transBegin(); // <<< START TRANSACTION
                $currentVariant = $variantModel->where('id', $id)->first(); // Get the latest one before the update
                // update data
                log_message('debug', json_encode($data));
                $variantModel->update($id, $data);
                // check for updated stock
                log_message('debug', 'inventory: new ' . $data['inventory_count'] . ' VS current ' . $currentVariant['inventory_count']);
                if ($data['inventory_count'] != $currentVariant['inventory_count']) {
                    $change    = $data['inventory_count'] - $currentVariant['inventory_count'];
                    $inventory = [
                        'variant_id'      => $id,
                        'activity_key'    => 'update',
                        'quantity_change' => $change,
                        'new_inventory'   => $data['inventory_count'],
                    ];
                    log_message('debug', json_encode($inventory));
                    $inventoryModel->insert($inventory);
                }
                $productModel->updateLowestPrices($data['product_id']);
                log_message('debug', 'done');
                if ($db->transStatus() === false) {
                    $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.db-issue')
                    ]);
                }
                $db->transCommit();
                // Also remove cache product_variant_info-##
                $cache    = Services::cache();
                $cacheKey = 'product_variant_info-' . $id;
                if ($cache->get($cacheKey)) {
                    $cache->delete($cacheKey);
                }
                log_message('debug', 'updated cache');
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'id'      => $id,
                    'message' => lang('System.response-msg.success.data-saved'),
                ]);
            } else {
                $db->transBegin(); // <<< START TRANSACTION
                $data['variant_slug'] = generate_slug($data['product_id'] . $data['variant_name']);
                log_message('debug', json_encode($data));
                $variantModel->insert($data);
                $id                   = $variantModel->getInsertID();
                $inventory            = [
                    'variant_id'      => $id,
                    'activity_key'    => 'update',
                    'quantity_change' => $data['inventory_count'],
                    'new_inventory'   => $data['inventory_count'],
                ];
                log_message('debug', json_encode($inventory));
                $inventoryModel->insert($inventory);
                $productModel->updateLowestPrices($data['product_id']);
                log_message('debug', 'done updating');
                if ($db->transStatus() === false) {
                    $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_ERR,
                        'message' => lang('System.response-msg.error.db-issue')
                    ]);
                }
                log_message('debug', 'committing');
                $db->transCommit();
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_OK,
                    'id'      => $variantModel->getInsertID(),
                    'message' => lang('System.response-msg.success.data-saved'),
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function product_variant_inventory(int $productId, int $variantId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $productLink    = base_url('admin/product/' . $productId);
        $variantId      = $variantId / ID_MASKED_PRIME;
        $variantModel   = new ProductVariantModel();
        $variant        = $variantModel->getVariantInformation($variantId);
        $productName    = ($variant['product_local_names'][$session->lang] ?? $variant['product_name']);
        $variantName    = ($variant['variant_local_names'][$session->lang] ?? $variant['variant_name']);
        $data = [
            'slug'       => 'product-variant-inventory',
            'lang'       => $this->request->getLocale(),
            'breadcrumb' => [
                [
                    'url'        => base_url('admin/product'),
                    'page_title' => lang('Admin.pages.product'),
                ],
                [
                    'url'        => $productLink,
                    'page_title' => lang('Admin.pages.product-manage'),
                ]
            ],
            'itemTitle'  => "$productName / $variantName",
            'variant'    => $variant,
        ];
        return view('admin/product_variant_inventory', $data);
    }

    /**
     * @param int $productId
     * @param int $variantId
     * @return ResponseInterface
     */
    public function product_variant_inventory_post(int $productId, int $variantId): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('DataTable');
        }
        $variantId      = $variantId / ID_MASKED_PRIME;
        $inventoryModel = new ProductVariantInventoryModel();
        $start          = (int) $this->request->getPost('start');
        $length         = (int) $this->request->getPost('length');
        $startDate      = $this->request->getPost('start_date');
        $endDate        = $this->request->getPost('end_date');
        $data           = $inventoryModel->getDataTable($variantId, $start, $length, $startDate, $endDate);
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $data['recordsTotal'],
            'recordsFiltered' => $data['recordsFiltered'],
            'data'            => $data['data']
        ]);
    }

    /**
     * Manage product category
     * @return string
     */
    public function product_category(): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $data = [
            'slug'           => 'product-category',
            'lang'           => $this->request->getLocale(),
        ];
        return view('admin/product_category', $data);
    }

    /**
     * Manage product
     * @return ResponseInterface
     */
    public function product_category_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        $categoryModel = new ProductCategoryModel();
        return $this->response->setJSON([
            'data' => $categoryModel->getDataTable($session->business['business_id'])
        ]);
    }

    /**
     * @param int $categoryId
     * @return string
     */
    public function product_category_manage(int $categoryId): string
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('string');
        }
        $categoryId    = $categoryId / ID_MASKED_PRIME;
        $categoryModel = new ProductCategoryModel();
        $category      = [];
        $mode          = 'new';
        if (0 < $categoryId) {
            $mode     = 'edit';
            $category = $categoryModel->findRow($categoryId);
            if (empty($category)) {
                throw PageNotFoundException::forPageNotFound();
            }
            $category['category_local_names'] = json_decode($category['category_local_names'], true);
        }
        $data = [
            'slug'       => 'product-category-manage',
            'lang'       => $this->request->getLocale(),
            'breadcrumb' => [
                [
                    'url'        => base_url('admin/product/category'),
                    'page_title' => lang('Admin.pages.product-category'),
                ]
            ],
            'mode'       => $mode,
            'category'   => $category,
        ];
        return view('admin/product_category_manage', $data);
    }

    public function product_category_manage_post(): ResponseInterface
    {
        $session = session();
        if (!in_array($session->user_role, ['OWNER', 'MANAGER'])) {
            return $this->forbiddenResponse('ResponseInterface');
        }
        try {
            $categoryModel         = new ProductCategoryModel();
            $id                    = $this->request->getPost('id');
            $data['category_name'] = $this->request->getPost('category_name');
            $languages             = get_available_locales('short');
            $names                 = [];
            foreach ($languages as $code => $name) {
                $names[$code] = $this->request->getPost('category_local_names_' . $code);
            }
            $data['category_local_names'] = json_encode($names, JSON_UNESCAPED_UNICODE);
            if (0 < $id) {
                if ($categoryModel->update($id, $data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            } else {
                $data['business_id'] = $session->business['business_id'];
                if ($categoryModel->insert($data)) {
                    return $this->response->setJSON([
                        'status'  => STATUS_RESPONSE_OK,
                        'message' => lang('System.response-msg.success.data-saved'),
                    ]);
                }
            }
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => lang('System.response-msg.error.db-issue'),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
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

    /**
     * PHPInfo
     * @return string
     */
    public function phpinfo(): string
    {
        return view('admin/phpinfo');
    }

}