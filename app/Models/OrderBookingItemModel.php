<?php

namespace App\Models;

use App\Models\AppBaseModel;

class OrderBookingItemModel extends AppBaseModel
{
    protected $table         = 'order_booking_item';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id',
        'order_id',
        'service_variant_id',
        'service_name',
        'service_variant_name',
        'booking_quantity',
        'unit_price',
        'booking_subtotal',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function bookScheduledSession(int $orderMasterId, int $serviceVariantId, string $serviceName, string $variantName,
                                         int $bookingQuantity, float $unitPrice, float $subtotal, int $sessionMasterId): string
    {
        try {
            $sessionMasterModel = new SessionMasterModel();
            $sessionMaster      = $sessionMasterModel->findRow($sessionMasterId);
            if (empty($sessionMaster)) {
                return lang('Checkout.error.session-unavailable');
            } else if ('OPEN' != $sessionMaster['SESSION_TYPE']) {
                return lang('Checkout.error.session-unavailable');
            }
            $capacity    = $sessionMaster['session_capacity'];
            $utilized    = $sessionMaster['session_capacity_utilized'];
            $new_balance = $utilized + $bookingQuantity;
            if ($new_balance > $capacity) {
                return lang('Checkout.error.session-full');
            }
            // not full, can register - update utilization and add booking item
            $sessionMasterModel->update($sessionMasterId, ['session_capacity_utilized' => $new_balance]);
            $bookingItem = [
                'order_id'             => $orderMasterId,
                'service_variant_id'   => $serviceVariantId,
                'service_name'         => $serviceName,
                'service_variant_name' => $variantName,
                'booking_quantity'     => $bookingQuantity,
                'unit_price'           => $unitPrice,
                'booking_subtotal'     => $subtotal,
            ];
            $this->insert($bookingItem);
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function bookAdhocSession(int $orderMasterId, string $orderNumber, int $serviceVariantId, string $serviceName, string $variantName,
                                     int $bookingQuantity, float $unitPrice, float $subtotal,
                                     string $startTime, string $endTime, int $branchId, string $branchTimezone, int $userId, string $resourceIds): string
    {
        try {
            // time
            $startObj = new \DateTime($startTime);
            $endObj   = new \DateTime($endTime);
            $utcTz    = new \DateTimeZone('UTC');
            $startStr = $startObj->setTimezone($utcTz)->format('Y-m-d H:i:s');
            $endStr   = $endObj->setTimezone($utcTz)->format('Y-m-d H:i:s');
            $branchTz = new \DateTimeZone($branchTimezone);
            $dtStart  = $startObj->setTimezone($branchTz)->format('Y-m-d');
            $dtEnd    = $endObj->setTimezone($branchTz)->format('Y-m-d');
            // model
            $allocationStaffModel    = new AllocationStaffModel();
            $allocationResourceModel = new AllocationResourceModel();
            $sessionMasterModel      = new SessionMasterModel();
            $sessionBreakdownModel   = new SessionBreakdownModel();
            // chk conflict
            if (0 < $userId) {
                $conflict = $allocationStaffModel->checkStaffConflict($userId, $startStr, $endStr);
                log_message('debug', 'user conflict = ' . json_encode($conflict));
                if (!empty($conflict)) {
                    log_message('debug', 'conflict detected on staff!');
                    return lang('Checkout.error.staff-conflict');
                }
            }
            $resourceId = 0;
            if (!empty($resourceIds)) {
                $explodeIds = explode(',', $resourceIds);
                foreach ($explodeIds as $rid) {
                    if (is_int($rid)) {
                        $conflict = $allocationResourceModel->checkResourceConflict($rid, $startStr, $endStr);
                        if (empty($conflict)) {
                            $resourceId = $rid;
                            break;
                        }
                    }
                }
                if (0 == $resourceId) {
                    return lang('Checkout.error.resource-conflict');
                }
            }
            // no conflict found - insert
            $sessionMaster      = [
                'branch_id'                 => $branchId,
                'service_variant_id'        => $serviceVariantId,
                'session_type'              => 'SPECIFIC',
                'session_capacity'          => 1,
                'session_capacity_utilized' => 1,
                'short_description'         => $serviceName . ' - ' . $variantName . ' - ' . $orderNumber,
                'date_start'                => $dtStart,
                'date_end'                  => $dtEnd,
            ];
            $sessionMasterId    = $sessionMasterModel->insert($sessionMaster);
            $sessionBreakdown   = [
                'session_id' => $sessionMasterId,
                'time_start' => $startStr,
                'time_end'   => $endStr,
            ];
            $sessionBreakdownId = $sessionBreakdownModel->insert($sessionBreakdown);
            if (0 < $userId) {
                $allocationStaff   = [
                    'user_id'              => $userId,
                    'session_breakdown_id' => $sessionBreakdownId,
                    'allocation_type'      => 'SESSION'
                ];
                $allocationStaffModel->insert($allocationStaff);
            }
            if (0 < $resourceId) {
                $allocationResource   = [
                    'resource_id'          => $resourceId,
                    'session_breakdown_id' => $sessionBreakdownId,
                    'allocation_type'      => 'SESSION'
                ];
                $allocationResourceModel->insert($allocationResource);
            }
            $bookingItem   = [
                'order_id'             => $orderMasterId,
                'service_variant_id'   => $serviceVariantId,
                'service_name'         => $serviceName,
                'service_variant_name' => $variantName,
                'booking_quantity'     => $bookingQuantity,
                'unit_price'           => $unitPrice,
                'booking_subtotal'     => $subtotal,
            ];
            $this->insert($bookingItem);
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}