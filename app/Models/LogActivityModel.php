<?php

/**
 * *********************************************************************
 * THIS MODEL IS SYSTEM MODEL, PLEASE REFRAIN FROM MAKING
 * ANY CHANGES TO THIS FILE UNLESS YOU KNOW WHAT YOU ARE DOING.
 * *********************************************************************
 * Log Activity Model
 * @package App\Models
 */

namespace App\Models;

use CodeIgniter\Model;
use ReflectionException;

class LogActivityModel extends Model
{
    protected $table = 'log_activity';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'activity_key',
        'table_involved',
        'table_id_updated',
        'activity_detail',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';
    const string ACTIVITY_KEY_UPDATE = 'update-table';
    const string ACTIVITY_KEY_INSERT = 'insert-table';
    const string ACTIVITY_KEY_DELETE = 'delete-table';
    const string ACTIVITY_KEY_LOGIN  = 'login';
    const string ACTIVITY_KEY_AVATAR_UPLOAD = 'avatar-upload';
    const string ACTIVITY_KEY_AVATAR_DELETE = 'avatar-delete';

    /**
     * Insert log activity when user login
     * @param string $result
     * @return bool|int|string
     * @throws ReflectionException
     */
    public function insertLogin(string $result): bool|int|string
    {
        $session = session();
        $result  = [
            'result' => $result
        ];
        $data   = [
            'activity_key'     => self::ACTIVITY_KEY_LOGIN,
            'table_involved'   => 'session',
            'table_id_updated' => $session->user_id,
            'activity_detail'  => json_encode($result),
            'created_by'       => $session->user_id
        ];
        return $this->insert($data);
    }

    /**
     * Insert log activity for insert/update/delete
     * @param string $table_involved
     * @param int $id
     * @param array $row
     * @param string $activity_key (optional)
     * @return void
     * @throws ReflectionException
     */
    public function insertLog(string $table_involved, int $id, array $row, string $activity_key = self::ACTIVITY_KEY_UPDATE): void
    {
        $session = session();
        $data    = [
            'activity_key'     => $activity_key,
            'table_involved'   => $table_involved,
            'table_id_updated' => $id,
            'activity_detail'  => json_encode($row),
            'created_by'       => $session->user_id
        ];
        $this->insert($data);
    }

}