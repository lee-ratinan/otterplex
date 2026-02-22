<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderCommentModel extends AppBaseModel
{
    protected $table         = 'order_comment';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id',
        'order_id',
        'comment_type',
        'comment_code',
        'comment_value',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}