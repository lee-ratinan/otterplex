<?php

namespace App\Models;

class BusinessUserLanguageModel extends AppBaseModel
{
    protected $table = 'business_user_language';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'business_user_id',
        'language_code',
        'proficiency_level',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function get_language_proficiency(): array
    {
        return ['native', 'fluent', 'intermediate', 'beginner'];
    }
}