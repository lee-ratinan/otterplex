<?php

namespace App\Models;

class ReviewMasterModel extends AppBaseModel
{
    protected $table = 'review_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_id',
        'product_id',
        'service_id',
        'customer_id',
        'rating',
        'review_title',
        'review_body',
        'customer_masking',
        'review_status',
        'flag_for_aggregation',
        'reviewed_at',
        'reviewed_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}