<?php

namespace App\Models;

class TagMasterModel extends AppBaseModel
{
    protected $table            = 'tag_master';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'id',
        'tag_name',
        'tag_slug',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}