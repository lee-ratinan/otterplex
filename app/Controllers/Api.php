<?php

namespace App\Controllers;

use App\Models\AllocationResourceModel;
use App\Models\AllocationStaffModel;
use App\Models\BranchMasterModel;
use App\Models\BranchModifiedHoursModel;
use App\Models\BranchOpeningHoursModel;
use App\Models\BusinessMasterModel;
use App\Models\BusinessPaymentMethodModel;
use App\Models\BusinessShippingFeeModel;
use App\Models\CustomerAddressModel;
use App\Models\CustomerMasterModel;
use App\Models\OrderBookingItemModel;
use App\Models\OrderLineAdjustmentModel;
use App\Models\OrderLineItemModel;
use App\Models\OrderMasterModel;
use App\Models\OrderPaymentModel;
use App\Models\ProductMasterModel;
use App\Models\ProductVariantModel;
use App\Models\ResourceMasterModel;
use App\Models\ServiceMasterModel;
use App\Models\ServiceStaffModel;
use App\Models\ServiceVariantModel;
use App\Models\SessionMasterModel;
use CodeIgniter\HTTP\ResponseInterface;
use libphonenumber\geocoding\data\id\Id_62;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Api extends BaseController
{

    private function getOrderData(string $orderNumber, string $languageCode): array
    {
        $orderMasterModel         = new OrderMasterModel();
        $orderLineItemModel       = new OrderLineItemModel();
        $orderLineAdjustmentModel = new OrderLineAdjustmentModel();
        $orderBookingItemModel    = new OrderBookingItemModel();
        $orderPaymentModel        = new OrderPaymentModel();
        $branchModel              = new BranchMasterModel();
        $customerModel            = new CustomerMasterModel();
        $customerAddressModel     = new CustomerAddressModel();
        // DATA
        $orderMaster              = $orderMasterModel->where('order_number', $orderNumber)->first();
        if (empty($orderMaster)) {
            return [];
        }
        $orderId                  = $orderMaster['id'];
        $branchName               = '';
        if (!empty($orderMaster['collection_branch_id'])) {
            $branch      = $branchModel->findRow($orderMaster['collection_branch_id']);
            $branchNames = json_decode($branch['branch_local_names'], true);
            $branchName  = $branchNames[$languageCode] ?? $branch['branch_name'];
        }
        return [
            'customer'         => [
                'data'    => $customerModel->findRow($orderMaster['customer_id']),
                'address' => (empty($orderMaster['customer_address_id']) ? [] : $customerAddressModel->findRow($orderMaster['customer_address_id'])),
            ],
            'order_id'         => $orderMaster['id'],
            'order_number'     => $orderNumber,
            'order_subtotal'   => $orderMaster['order_subtotal'],
            'order_adjustment' => $orderMaster['order_adjustment'],
            'order_total'      => $orderMaster['order_total'],
            'shipping'         => [
                'option'            => $orderMaster['shipping_option'],
                'collection_branch' => $branchName,
                'status'            => $orderMaster['shipping_status'],
            ],
            'payment'          => [
                'method' => $orderMaster['payment_method'],
                'status' => $orderMaster['financial_status'],
            ],
            'order_status'     => $orderMaster['order_status'],
            'customer_comment' => $orderMaster['customer_comment'],
            'line_items'       => $orderLineItemModel->where('order_id', $orderMaster['id'])->findAll(),
            'booking_items'    => $orderBookingItemModel->where('order_id', $orderMaster['id'])->findAll(),
            'adjustment_lines' => $orderLineAdjustmentModel->where('order_id', $orderMaster['id'])->findAll(),
            'payment_lines'    => $orderPaymentModel->where('order_id', $orderMaster['id'])->findAll(),
        ];
    }

    public function business_search(string $languageCode, string $countryCode): ResponseInterface
    {
        $query         = $this->request->getGet('query');
        $businessModel = new BusinessMasterModel();
        $countryCode   = strtolower($countryCode);
        $languageCode  = strtolower($languageCode);
        $rawResults    = $businessModel
            ->select('business_master.*, business_type.type_name, business_type.type_local_names')
            ->join('business_type', 'business_type.id = business_master.business_type_id')
            ->where('country_code', $countryCode)
            ->where('live_status', 'Y')
            ->groupStart()
            ->like('business_name', $query)
            ->orLike('business_local_names', $query)
            ->groupEnd()
            ->orderBy('business_name')
            ->limit(10)
            ->findAll();
        $results       = [];
        foreach ($rawResults as $row) {
            $local_names = json_decode($row['business_local_names'], true);
            $name        = $local_names[$languageCode] ?? $row['business_name'];
            $types       = json_decode($row['type_local_names'], true);
            $type        = $types[$languageCode] ?? $row['business_type'];
            $results[]   = [
                'link'         => getenv('marketplace_site') . '@' . $row['business_slug'],
                'businessType' => $type,
                'name'         => $name,
                'businessLogo' => (!empty($row['business_logo']) ? base_url('/file/business_' . $row['business_logo']) : '')
            ];
        }
        return $this->response->setJSON([
            'query'   => $query,
            'results' => $results
        ]);
    }

    public function business_retrieve(string $languageCode, string $countryCode): ResponseInterface
    {
        $this->request->setLocale($languageCode);
        $session       = session();
        $session->set('lang', $languageCode);
        $query         = $this->request->getGet('business-slug');
        $businessModel = new BusinessMasterModel();
        // BUSINESS
        $business      = $businessModel
            ->select('business_master.*, business_type.type_name, business_type.type_local_names')
            ->join('business_type', 'business_type.id = business_master.business_type_id')
            ->where('business_slug', $query)
            ->where('country_code', $countryCode)
            ->where('live_status', 'Y')
            ->first();
        if (empty($business)) {
            return $this->response->setJSON([
                'query'    => $query,
                'business' => []
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }
        $local_names                            = json_decode($business['business_local_names'], true);
        $type_names                             = json_decode($business['type_local_names'], true);
        $mart_meta_descriptions                 = json_decode($business['mart_meta_description'], true);
        $mart_meta_keywords_array               = json_decode($business['mart_meta_keywords'], true);
        $mart_store_intro_paragraphs            = json_decode($business['mart_store_intro_paragraph'], true);
        $business['social_media']               = json_decode($business['social_media'], true);
        $business['country']                    = get_country_name_single_language($business['country_code'], $languageCode);
        $business['business_name']              = $local_names[$languageCode] ?? $business['business_name'];
        $business['type_name']                  = $type_names[$languageCode] ?? $business['type_name'];
        $business['mart_meta_description']      = $mart_meta_descriptions[$languageCode] ?? '';
        $business['mart_meta_keywords']         = $mart_meta_keywords_array[$languageCode] ?? '';
        $business['mart_store_intro_paragraph'] = $mart_store_intro_paragraphs[$languageCode] ?? '';
        $business['contact_phone_number_shown'] = '';
        if (!empty($business['contact_phone_number'])) {
            $phone_util = PhoneNumberUtil::getInstance();
            $phone_obj  = $phone_util->parse($business['contact_phone_number'], $business['country_code']);
            $business['contact_phone_number_shown'] = $phone_util->format($phone_obj, PhoneNumberFormat::NATIONAL);
        }
        $business['contact_phone_number']       = $business['contact_phone_number'] ?? '';
        unset($business['business_local_names']);
        unset($business['type_local_names']);
        if (!empty($business['business_logo'])) {
            $business['business_logo'] = base_url('/file/business_' . $business['business_logo']);
        }
        // BRANCHES
        $branchModel = new BranchMasterModel();
        $branchRaw   = $branchModel
            ->where('business_id', $business['id'])
            ->findAll();
        $bIds        = [];
        $branches    = [];
        foreach ($branchRaw as $branch) {
            $local_names              = json_decode($branch['branch_local_names'], true);
            $branch['branch_name']    = $local_names[$languageCode] ?? $branch['branch_name'];
            $branch['subdivision']    = get_country_subdivisions($business['country_code'], $branch['subdivision_code']);
            unset($branch['branch_local_names']);
            unset($branch['subdivision_code']);
            $bIds[]                   = $branch['id'];
            $branch['hours']          = [];
            $branch['modified_hours'] = [];
            $branches[$branch['id']]  = $branch;
        }
        // HOURS
        $hoursModel   = new BranchOpeningHoursModel();
        $mhModel      = new BranchModifiedHoursModel();
        $yesterday    = date('Y-m-d', strtotime('yesterday'));
        $hoursRaw     = $hoursModel->whereIn('branch_id', $bIds)->findAll();
        $modifiedRaw  = $mhModel->whereIn('branch_id', $bIds)->where('modified_hours_date >=', $yesterday)->findAll();
        foreach ($hoursRaw as $row) {
            $branches[$row['branch_id']]['hours'][$row['day_of_the_week']] = [
                'opening_hours' => $row['opening_hours'],
                'closing_hours' => $row['closing_hours'],
            ];
        }
        foreach ($modifiedRaw as $row) {
            $open  = null;
            $close = null;
            if ('CLOSED' != $row['modified_type']) {
                $open  = $row['updated_opening_hours'];
                $close = $row['updated_closing_hours'];
            }
            $branches[$row['branch_id']]['modified_hours'][] = [
                'date'          => $row['modified_hours_date'],
                'opening_hours' => $open,
                'closing_hours' => $close,
            ];
        }
        $business['branches'] = $branches;
        // SERVICES
        $serviceModel = new ServiceMasterModel();
        $svModel      = new ServiceVariantModel();
        $servicesRaw  = $serviceModel->where('business_id', $business['id'])->findAll();
        $services     = [];
        $sId          = [];
        foreach ($servicesRaw as $service) {
            $local_names                    = json_decode($service['service_local_names'], true);
            $descriptions                   = json_decode($service['service_description'], true);
            $service['service_name']        = $local_names[$languageCode] ?? $service['service_name'];
            $service['service_description'] = $descriptions[$languageCode] ?? '';
            unset($service['service_local_names']);
            if (!empty($service['service_image'])) {
                $service['service_image'] = base_url('file/' . $service['service_image']);
            }
            $services[$service['id']] = $service;
            $sId[]                    = $service['id'];
        }
        $business['services'] = [];
        if (!empty($sId)) {
            $sVariantRaw = $svModel->whereIn('service_id', $sId)->findAll();
            foreach ($sVariantRaw as $sv) {
                $local_names        = json_decode($sv['variant_local_names'], true);
                $sv['variant_name'] = $local_names[$languageCode] ?? $sv['variant_name'];
                unset($sv['variant_local_names']);
                $services[$sv['service_id']]['variants'][$sv['id']] = $sv;
            }
            $business['services'] = $services;
        }
        // PRODUCTS
        $productModel = new ProductMasterModel();
        $pvModel      = new ProductVariantModel();
        $productRaw   = $productModel->where('business_id', $business['id'])->findAll();
        $products     = [];
        $pId          = [];
        foreach ($productRaw as $product) {
            $local_names                    = json_decode($product['product_local_names'], true);
            $descriptions                   = json_decode($product['product_description'], true);
            $product['product_name']        = $local_names[$languageCode] ?? $product['product_name'];
            $product['product_description'] = $descriptions[$languageCode] ?? '';
            unset($product['product_local_names']);
            if (!empty($product['product_image'])) {
                $product['product_image'] = base_url('file/' . $product['product_image']);
            }
            $products[$product['id']] = $product;
            $pId[]                    = $product['id'];
        }
        $business['products'] = [];
        if (!empty($pId)) {
            $pVariantRaw = $pvModel->whereIn('product_id', $pId)->findAll();
            foreach ($pVariantRaw as $pv) {
                $local_names        = json_decode($pv['variant_local_names'], true);
                $pv['variant_name'] = $local_names[$languageCode] ?? $pv['variant_name'];
                unset($pv['variant_local_names']);
                $products[$pv['product_id']]['variants'][] = $pv;
            }
            $business['products'] = $products;
        }
        // PAYMENT
        $paymentModel = new BusinessPaymentMethodModel();
        $payments     = $paymentModel->where('business_id', $business['id'])->findAll();
        $paymentFinal = [];
        foreach ($payments as $payment) {
            $paymentFinal[$payment['payment_method']] = [
                'id'                  => $payment['id'],
                'payment_method'      => $payment['payment_method'],
                'payment_instruction' => json_decode($payment['payment_instruction'], true),
            ];
        }
        $business['payments'] = $paymentFinal;
        // SHIPPING FEE
        $shippingModel              = new BusinessShippingFeeModel();
        $business['shipping_rates'] = $shippingModel->where('business_id', $business['id'])->findAll();
        return $this->response->setJSON([
            'query'    => $query,
            'business' => $business
        ]);
    }

    private function get_basic_business_info(int $businessId, int $serviceId, int $variantId): array
    {
        $businessModel = new BusinessMasterModel();
        $serviceModel  = new ServiceMasterModel();
        $variantModel  = new ServiceVariantModel();
        $businessId    = $businessId / ID_MASKED_PRIME;
        $serviceId     = $serviceId / ID_MASKED_PRIME;
        $variantId     = $variantId / ID_MASKED_PRIME;
        $business      = $businessModel->findRow($businessId);
        if (empty($business) || 'Y' != $business['live_status']) {
            return []; // wrong business ID or not active
        }
        $service = $serviceModel->findRow($serviceId);
        if (empty($service) || 'A' != $service['is_active']) {
            return []; // not found or not active
        }
        $variant = $variantModel->findRow($variantId);
        if (empty($variant) || 'A' != $variant['is_active'] || $variant['service_id'] != $service['id']) {
            return []; // not found or not active or wrong service (not the variant of the right service)
        }
        $variant['price_active_str']  = format_price($variant['price_active'], $business['currency_code']);
        $variant['duration']          = generate_duration_label($variant['service_duration_minutes']);
        return [
            'business' => $business,
            'service'  => $service,
            'variant'  => $variant,
        ];
    }

    public function get_sessions(string $languageCode, string $countryCode, int $businessId, int $serviceId, int $variantId): ResponseInterface
    {
        service('language')->setLocale($languageCode);
        $masterDetail = $this->get_basic_business_info($businessId, $serviceId, $variantId);
        if (empty($masterDetail)) {
            return $this->response->setJSON([]);
        }
        $variant                 = $masterDetail['variant'];
        $localNames              = json_decode($variant['variant_local_names'], true);
        $variant['variant_name'] = $localNames[$languageCode] ?? $variant['variant_name'];
        $dateFrom                = $this->request->getGet('date_from') ?? '';
        $dateTo                  = $this->request->getGet('date_to') ?? '';
        $branchId                = (int) $this->request->getGet('branch_id') ?? 0;
        if (0 < $branchId) {
            $branchId = intval($branchId / ID_MASKED_PRIME);
        }
        $sessionModel            = new SessionMasterModel();
        $sessions                = $sessionModel->getAvailableSessions($masterDetail['variant']['id'], $languageCode, $dateFrom, $dateTo, $branchId);
        return $this->response->setJSON([
            'variant_slug'             => $variant['variant_slug'],
            'variant_name'             => $variant['variant_name'],
            'schedule_type'            => $variant['schedule_type'],
            'variant_capacity'         => $variant['variant_capacity'],
            'price_active'             => $variant['price_active'],
            'price_compare'            => $variant['price_compare'],
            'service_duration_minutes' => $variant['service_duration_minutes'],
            'sessions'                 => (empty($sessions) ? null : $sessions)
        ]);
    }

    public function get_slots(string $languageCode, string $countryCode, int $businessId, int $serviceId, int $variantId): ResponseInterface
    {
        service('language')->setLocale($languageCode);
        $masterDetail = $this->get_basic_business_info($businessId, $serviceId, $variantId);
        if (empty($masterDetail)) {
            return $this->response->setJSON([1]);
        }
        // models:
        $resourcesModel    = new ResourceMasterModel();
        $serviceStaffModel = new ServiceStaffModel();
        $branchModel       = new BranchMasterModel();
        $raModel           = new AllocationResourceModel();
        $saModel           = new AllocationStaffModel();
        // process:
        $service                 = $masterDetail['service'];
        $variant                 = $masterDetail['variant'];
        $serviceNames            = json_decode($service['service_local_names'], true);
        $service['service_name'] = $serviceNames[$languageCode] ?? $service['service_name'];
        $localNames              = json_decode($variant['variant_local_names'], true);
        $variant['variant_name'] = $localNames[$languageCode] ?? $variant['variant_name'];
        $selectedDate            = $this->request->getGet('selected_date');
        $branchId                = (int) $this->request->getGet('branch_id') ?? 0; // Branch is required
        $branch                  = [];
        if (empty($selectedDate)) {
            $selectedDate = date('Y-m-d');
        }
        if (0 < $branchId) {
            $branchId = intval($branchId / ID_MASKED_PRIME);
            $branch   = $branchModel->findBranchInfoAndHoursByBranch($branchId, $selectedDate);
            $branch['branch_name'] = $branch['branch_local_names'][$languageCode] ?? $branch['branch_name'];
            unset($branch['branch_local_names']);
        }
        if (empty($branch)) {
            return $this->response->setJSON([2]);
        }
        // Fix possible slots
        if (!empty($branch['opening_hours'][0]) && !empty($branch['opening_hours'][1])) {
            try {
                $tzUTC    = new \DateTimeZone('UTC');
                $minutes  = $variant['service_duration_minutes'];
                $interval = new \DateInterval("PT30M"); // start every 30 minutes
                $duration = new \DateInterval("PT{$minutes}M"); // duration is $minutes minutes
                // calculate slots
                $strOpenHrs  = $branch['opening_hours'][0];
                $strCloseHrs = $branch['opening_hours'][1];
                $openHrs     = new \DateTime($strOpenHrs, $tzUTC);
                $closeHrs    = new \DateTime($strCloseHrs, $tzUTC);
                $possibleStartTimes = new \DatePeriod($openHrs, $interval, $closeHrs);
                $goodSlots   = [];
                foreach ($possibleStartTimes as $startTime) {
                    $endTime = clone $startTime;
                    $endTime->add($duration);
                    if ($endTime <= $closeHrs) {
                        $goodSlots[] = [
                            $startTime->format('Y-m-d\TH:i:s') . '+00:00',
                            $endTime->format('Y-m-d\TH:i:s') . '+00:00',
                        ];
                    }
                }
                $branch['slots'] = $goodSlots;
            } catch (\Exception $e) {
                log_message('error', $e->getMessage());
                return $this->response->setJSON([]);
            }
        } else {
            $branch['slots'] = [];
        }
        // get service staff list
        $branch['users']             = [];
        $branch['resources']         = [];
        $branch['availableSlots']    = [];
        $branch['slotCount']         = 0;
        if (!empty($branch['slots'])) {
            $branch['userConflicts']     = [];
            $branch['resourceConflicts'] = [];
            if (0 < $masterDetail['variant']['required_num_staff']) {
                // need staff
                $staffByService  = $serviceStaffModel->getStaffByServiceAndBranch($service['id'], $branchId);
                $branch['users'] = $staffByService;
                $userIds         = array_keys($staffByService);
                $userConflicts   = $saModel->checkStaffConflict($userIds, $branch['opening_hours'][0], $branch['opening_hours'][1]);
                foreach ($userConflicts as $row) {
                    $branch['userConflicts'][$row['user_id']][] = [
                        str_replace(' ', 'T', $row['time_start']) . '+00:00',
                        str_replace(' ', 'T', $row['time_end']) . '+00:00',
                    ];
                }
            }
            if (!empty($variant['required_resource_type_id'])) {
                // need resource
                $resourceRaw         = $resourcesModel->getResourceTypeForBranch($variant['required_resource_type_id']);
                $branch['resources'] = $resourceRaw;
                $resourceIds         = array_keys($resourceRaw);
                $resourceConflicts   = $raModel->checkResourceConflict($resourceIds, $branch['opening_hours'][0], $branch['opening_hours'][1]);
                foreach ($resourceConflicts as $row) {
                    $branch['resourceConflicts'][$row['resource_id']][] = [
                        str_replace(' ', 'T', $row['time_start']) . '+00:00',
                        str_replace(' ', 'T', $row['time_end']) . '+00:00',
                    ];
                }
            }
            foreach ($branch['slots'] as $slot) {
                $slotStart         = $slot[0];
                $slotEnd           = $slot[1];
                $needUser          = false;
                $needResource      = false;
                $userAvailable     = [];
                $resourceAvailable = [];
                if (!empty($branch['users'])) {
                    $needUser      = true;
                    foreach ($branch['users'] as $userId => $user) {
                        if (isset($branch['userConflicts'][$userId])) {
                            $foundConflict = false;
                            foreach ($branch['userConflicts'][$userId] as $bookedTimes) {
                                if ($bookedTimes[0] < $slotEnd && $bookedTimes[1] > $slotStart) {
                                    $foundConflict = true;
                                    break;
                                }
                            }
                            if (!$foundConflict) {
                                $userAvailable[$userId] = $branch['users'][$userId]['user_public_name'];
                            }
                        } else {
                            $userAvailable[$userId] = $branch['users'][$userId]['user_public_name'];
                        }
                    }
                }
                if (!empty($branch['resources'])) {
                    $needResource      = true;
                    foreach ($branch['resources'] as $resourceId => $resource) {
                        if (isset($branch['resourceConflicts'][$resourceId])) {
                            $foundConflict = false;
                            foreach ($branch['resourceConflicts'][$resourceId] as $bookedTimes) {
                                if ($bookedTimes[0] < $slotEnd && $bookedTimes[1] > $slotStart) {
                                    $foundConflict = true;
                                    break;
                                }
                            }
                            if (!$foundConflict) { // $branch['resources']
                                $resourceAvailable[$resourceId] = $branch['resources'][$resourceId]['resource_name'];
                            }
                        } else {
                            $resourceAvailable[$resourceId] = $branch['resources'][$resourceId]['resource_name'];
                        }
                    }
                }
                if ($needUser && empty($userAvailable)) {
                    continue;
                }
                if ($needResource && empty($resourceAvailable)) {
                    continue;
                }
                $branch['availableSlots'][] = [
                    'start'     => $slotStart,
                    'end'       => $slotEnd,
                    'users'     => $userAvailable,
                    'resources' => $resourceAvailable,
                ];
            }
            unset($branch['userConflicts']);
            unset($branch['resourceConflicts']);
            $branch['slotCount'] = count($branch['availableSlots']);
        }
        unset($branch['slots']);
        return $this->response->setJSON([
            'service_slug'             => $service['service_slug'],
            'service_name'             => $service['service_name'],
            'variant_slug'             => $variant['variant_slug'],
            'variant_name'             => $variant['variant_name'],
            'schedule_type'            => $variant['schedule_type'],
            'price_active'             => $variant['price_active'],
            'price_active_str'         => $variant['price_active_str'],
            'service_duration_minutes' => $variant['service_duration_minutes'],
            'duration'                 => $variant['duration'],
            'branch'                   => $branch,
        ]);
    }

    public function business_checkout(string $languageCode, string $countryCode): ResponseInterface
    {
        service('language')->setLocale($languageCode);
        try {
            $body      = $this->request->getBody();
            $bodyArray = json_decode($body, true);
            $cart      = @$bodyArray['cart'];
            log_message('debug', "Checking cart");
            log_message('debug', "Cart: " . json_encode($cart));
            $businessId = $cart['business_id'];
            $db         = \Config\Database::connect();
            $db->transBegin();
            // FIND BUSINESS
            $businessModel  = new BusinessMasterModel();
            $branchModel    = new BranchMasterModel();
            $businessMaster = $businessModel->findRow($businessId);
            $branchMaster   = $branchModel->where('business_id', $businessId)->orderBy('id', 'ASC')->first();
            $timezone       = $branchMaster['timezone_code'];
            // TABLE: customer_master
            $customerMasterModel = new CustomerMasterModel();
            $customerMaster      = $cart['customer_detail'];
            $customerMasterId    = $customerMasterModel->checkEmailAddress($customerMaster);
            log_message('debug', "Customer Master: {$customerMasterId} " . json_encode($customerMaster));
            $customerAddressId   = null;
            // TABLE: customer_address
            if (!empty($cart['customer_address_detail'])) {
                $customerAddressModel = new CustomerAddressModel();
                $customerAddress      = $cart['customer_address_detail'];
                $customerAddressId    = $customerAddressModel->checkAddress($customerMasterId, $customerAddress);
                log_message('debug', "Customer Address: {$customerAddressId} " . json_encode($customerAddress));
            }
            $orderMasterModel        = new OrderMasterModel();
            $adjustment              = $cart['order_total'] - $cart['order_subtotal'];
            $shipping_status         = ('SHIPPING' == $cart['shipping_option'] ? 'OPEN' : 'NOT_APPLICABLE');
            $cart['shipping_option'] = str_replace('-', '_', $cart['shipping_option']);
            $orderMaster             = [
                'business_id'          => $businessId,
                'customer_id'          => $customerMasterId,
                'customer_address_id'  => $customerAddressId,
                'order_number'         => '',
                'order_subtotal'       => $cart['order_subtotal'],
                'order_adjustment'     => $adjustment,
                'order_total'          => $cart['order_total'],
                'shipping_option'      => $cart['shipping_option'],
                'payment_method'       => $cart['payment_method'],
                'order_status'         => 'OPEN',
                'financial_status'     => 'PENDING',
                'shipping_status'      => $shipping_status,
                'staff_comment'        => null,
                'customer_comment'     => $cart['customer_comment'],
            ];
            if (!empty($cart['collection_branch_id'])) {
                $orderMaster['collection_branch_id'] = ($cart['collection_branch_id'] / ID_MASKED_PRIME);
            }
            $orderMasterId = $orderMasterModel->insert($orderMaster);
            log_message('debug', "Order Master: {$orderMasterId} " . json_encode($orderMaster));
            $orderNumber   = generate_order_number($orderMasterId, $timezone);
            $orderMasterModel->update($orderMasterId, ['order_number' => $orderNumber]);
            log_message('debug', "Order Number: {$orderMasterId} = " . $orderNumber);
            // line item
            $orderLineModel    = new OrderLineItemModel();
            $orderBookingModel = new OrderBookingItemModel();
            $orderAdjustModel  = new OrderLineAdjustmentModel();
            $errorMessages     = [];
            if (!empty($cart['line_items'])) {
                foreach ($cart['line_items'] as $row) {
                    $error = $orderLineModel->buyItem($orderMasterId, $row['product_variant_id'], $row['product_name'], $row['product_variant_name'], $row['line_quantity'], $row['unit_price'], $row['line_subtotal'], $row['item_need_delivery']);
                    if (!empty($error)) {
                        log_message('debug', $error);
                        $errorMessages[] = $error;
                    }
                    $log_data = [$orderMasterId, $row['product_variant_id'], $row['product_name'], $row['product_variant_name'], $row['line_quantity'], $row['unit_price'], $row['line_subtotal'], $row['item_need_delivery']];
                    log_message('debug', "Buy Item: " . implode(', ', $log_data));
                }
            }
            // booking item: scheduled
            if (!empty($cart['scheduled_service'])) {
                foreach ($cart['scheduled_service'] as $row) {
                    $error = $orderBookingModel->bookScheduledSession($orderMasterId, $row['service_variant_id'], $row['service_name'], $row['service_variant_name'],
                                $row['booking_quantity'], $row['unit_price'], $row['booking_subtotal'], $row['session_id']);
                    if (!empty($error)) {
                        log_message('debug', $error);
                        $errorMessages[] = $error;
                    }
                    $log_data = [$orderMasterId, $row['service_variant_id'], $row['service_name'], $row['service_variant_name'], $row['booking_quantity'], $row['unit_price'], $row['booking_subtotal'], $row['session_id']];
                    log_message('debug', "Scheduled Item: " . implode(', ', $log_data));
                }
            }
            // booking item: adhoc
            if (!empty($cart['adhoc_service'])) {
                foreach ($cart['adhoc_service'] as $row) {
                    $now   = (new \DateTime())->setTimezone(new \DateTimeZone('UTC'));
                    $start = (new \DateTime($row['time_start_utc']))->setTimezone(new \DateTimeZone('UTC'));
                    if ($start < $now) {
                        $startStr = $start->format('Y-m-d H:i:s');
                        $nowStr   = $now->format('Y-m-d H:i:s');
                        log_message('debug', "ERROR: {$startStr} < {$nowStr}");
                        $errorMessages[] = lang('Checkout.error.start-in-the-past');
                    }
                    $branch   = $branchModel->findRow($row['branch_id']);
                    $branchTz = $branch['timezone_code'];
                    $error    = $orderBookingModel->bookAdhocSession($orderMasterId, $orderNumber, $row['service_variant_id'], $row['service_name'], $row['service_variant_name'],
                            $row['booking_quantity'], $row['unit_price'], $row['booking_subtotal'],
                            $row['time_start_utc'], $row['time_end_utc'], $row['branch_id'], $branchTz, $row['user_id'], $row['resource_ids']);
                    if (!empty($error)) {
                        log_message('debug', $error);
                        $errorMessages[] = $error;
                    }
                    $log_data = [$orderMasterId, $orderNumber, $row['service_variant_id'], $row['service_name'], $row['service_variant_name'],
                                 $row['booking_quantity'], $row['unit_price'], $row['booking_subtotal'],
                                 $row['time_start_utc'], $row['time_end_utc'], $row['branch_id'], $branchTz, $row['user_id'], $row['resource_ids']];
                    log_message('debug', "Adhoc Service: " . implode(', ', $log_data));
                }
            }
            // adjustment lines
            if (!empty($cart['adjustment_items'])) {
                foreach ($cart['adjustment_items'] as $adj_type => $row) {
                    $insert = [
                        'order_id'        => $orderMasterId,
                        'adjustment_type' => $adj_type,
                        'line_detail'     => $row['detail'],
                        'line_amount'     => $row['amount'],
                    ];
                    $orderAdjustModel->insert($insert);
                }
            }
            if ($db->transStatus() === false) {
                $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_ERR,
                    'message' => lang('System.response-msg.error.db-issue'),
                    'order'   => []
                ]);
            } else if (!empty($errorMessages)) {
                $db->transRollback(); // <<< ROLLBACK (Undoes changes from all Models)
                return $this->response->setJSON([
                    'status'  => STATUS_RESPONSE_ERR,
                    'message' => implode(', ', $errorMessages),
                    'order'   => []
                ]);
            }
            $db->transCommit();
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_OK,
                'message' => '',
                'order'   => $this->getOrderData($orderNumber, $languageCode)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
                'order'   => []
            ]);
        }
    }

    public function order_search(string $languageCode, string $countryCode, string $orderNumber): ResponseInterface
    {
        service('language')->setLocale($languageCode);
        try {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_OK,
                'message' => '',
                'order'   => $this->getOrderData($orderNumber, $languageCode)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => STATUS_RESPONSE_ERR,
                'message' => $e->getMessage(),
                'order'   => []
            ]);
        }
    }
}