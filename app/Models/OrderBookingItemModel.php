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

    public function bookScheduledSession(): string
    {
        return '';
    }

    public function bookAdhocSession(): string
    {
        return '';
    }
}