<?php

namespace App\Models;

class BranchOpeningHoursModel extends AppBaseModel
{
    protected $table = 'branch_opening_hours';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'branch_id',
        'day_of_the_week',
        'opening_hours',
        'closing_hours',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}