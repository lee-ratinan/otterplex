<?php

namespace App\Models;

class BusinessMasterModel extends AppBaseModel
{
    protected $table = 'business_master';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_type_id',
        'business_name',
        'business_slug',
        'business_local_names',
        'country_code',
        'currency_code',
        'tax_percentage',
        'tax_inclusive',
        'mart_primary_color',
        'mart_text_color',
        'mart_background_color',
        'business_logo',
        'contract_anchor_day',
        'contract_expiry',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}