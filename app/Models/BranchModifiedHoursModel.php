<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchModifiedHoursModel extends AppBaseModel
{
    protected $table = 'branch_modified_hours';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'branch_id',
        'modified_hours_date',
        'modified_reason',
        'modified_type',
        'updated_opening_hours',
        'updated_closing_hours',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}