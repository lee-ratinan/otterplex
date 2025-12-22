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
use App\Models\ProductCategoryModel;
use App\Models\ProductMasterModel;
use App\Models\ProductVariantInventoryModel;
use App\Models\ProductVariantModel;
use App\Models\ResourceMasterModel;
use App\Models\ResourceTypeModel;
use App\Models\ServiceMasterModel;
use App\Models\ServiceStaffModel;
use App\Models\ServiceVariantModel;
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
            $serviceModel = new ServiceMasterModel;
            $productModel = new ProductMasterModel;
            $staffModel   = new BranchUserModel;
            $branches     = $branchModel->where('business_id', $businessId)->findAll();
            $service_raw  = $serviceModel->select('service_master.id AS service_id, service_variant.variant_name, service_variant.variant_local_names, service_master.service_name, service_master.service_local_names')
                ->join('service_variant', 'service_master.id = service_variant.service_id', 'left outer')
                ->where('service_master.business_id', $businessId)->findAll();
            $services     = [];
            $product_raw  = $productModel->select('product_master.id AS product_id, product_variant.variant_name, product_variant.variant_local_names, product_master.product_name, product_master.product_local_names')
                ->join('product_variant', 'product_variant.product_id = product_master.id', 'left outer')
                ->where('product_master.business_id', $businessId)->findAll();
            $products     = [];
            foreach ($service_raw as $service) {
                $service_names                 = json_decode($service['service_local_names'], true);
                $service_name                  = $service_names[$session->lang] ?? $service['service_name'];
                $id                            = $service['service_id'] * ID_MASKED_PRIME;
                $services[$id]['service_name'] = $service_name;
                $services[$id]['variants']     = [];
                if (!empty($service['variant_name'])) {
                    $variant_names               = json_decode($service['variant_local_names'], true);
                    $variant_name                = $variant_names[$session->lang] ?? $service['variant_name'];
                    $services[$id]['variants'][] = $variant_name;
                }
            }
            foreach ($product_raw as $product) {
                $product_names                 = json_decode($product['product_local_names'], true);
                $product_name                  = $product_names[$session->lang] ?? $product['product_name'];
                $id                            = $product['product_id'] * ID_MASKED_PRIME;
                $products[$id]['product_name'] = $product_name;
                $products[$id]['variants']     = [];
                if (!empty($product['variant_name'])) {
                    $variant_names               = json_decode($product['variant_local_names'], true);
                    $variant_name                = $variant_names[$session->lang] ?? $product['variant_name'];
                    $products[$id]['variants'][] = $variant_name;
                }
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
            $businessId          = $session->business['business_id'];
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
                    'branch_address', 'branch_postal_code', 'branch_status'
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
                $data['branch_local_names'] = json_encode($raw_data);
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
                $fields  = ['email_address', 'user_name_first', 'user_name_last', 'account_status'];
                $data    = [];
                foreach ($fields as $field) {
                    $data[$field] = $this->request->getPost($field);
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
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return $this->response->setJSON([
            'status'  => STATUS_RESPONSE_ERR,
            'message' => $message ?? '',
        ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
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
                $service['service_slug'],
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
        $count         = $categoryModel->where('business_id', $session->business_id)->countAllResults();
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
            'variant'    => $variant,
            'mode'       => $mode,
        ];
        return view('admin/product_variant', $data);
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