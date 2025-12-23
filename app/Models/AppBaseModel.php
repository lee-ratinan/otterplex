<?php

namespace App\Models;

use CodeIgniter\Config\Services;
use CodeIgniter\Model;
use ReflectionException;

class AppBaseModel extends Model
{
    protected LogActivityModel $logModel;
    private array $masked_fields = [
        'password_hash',
    ];

    const int HOURS_IN_SEC = 3600;

    public function __construct()
    {
        parent::__construct();
        $this->logModel = new LogActivityModel();
    }

    /**
     * Log the insert/update/delete
     * @param int $id
     * @param array $row
     * @param string $action
     * @throws ReflectionException
     */
    protected function logAction(int $id, array $row, string $action): void
    {
        if (!property_exists($this, 'table') || !$this->table) {
            log_message('error', 'Table name not set in model for logging');
            return;
        }
        $this->logModel->insertLog($this->table, $id, $row, $action);
    }

    /**
     * Retrieve data from cache first
     * If the data must be retrieved from the database directly, use ->find($id) or ->first()
     * @param int $id
     * @return array|null
     */
    public function findRow(int $id): ?array
    {
        // Cache
        $cache     = Services::cache();
        $cache_key = $this->table . '-' . $id;
        if ($cache->get($cache_key)) {
            return $cache->get($cache_key);
        }
        $row       = parent::find($id);
        $cache->save($cache_key, $row, self::HOURS_IN_SEC);
        return $row;
    }

    /**
     * Insert data into the database and log
     * @param array|null $row
     * @param bool $returnID
     * @return bool|int|string
     * @throws ReflectionException
     */
    public function insert($row = null, bool $returnID = true): bool|int|string
    {
        $session           = session();
        $row['created_by'] = $session->user_id;
        $result            = parent::insert($row, $returnID);
        if ($result) {
            // Log
            $id  = $this->getInsertID();
            $row = parent::find($id);
            foreach ($this->masked_fields as $field) {
                if (isset($row[$field])) {
                    $row[$field] = '******';
                }
            }
            $this->logAction($id, $row, LogActivityModel::ACTIVITY_KEY_INSERT);
            // Save cache
            $cache     = Services::cache();
            $cache_key = $this->table . '-' . $id;
            $cache->save($cache_key, $row, self::HOURS_IN_SEC);
        }
        return $result;
    }

    /**
     * Update the data in the database and log
     * @param int|null $id
     * @param array|null $row
     * @return bool
     * @throws ReflectionException
     */
    public function update($id = null, $row = null): bool
    {
        $result = parent::update($id, $row);
        if ($result) {
            // Log
            foreach ($this->masked_fields as $field) {
                if (isset($row[$field])) {
                    $row[$field] = '******';
                }
            }
            $this->logAction($id, $row, LogActivityModel::ACTIVITY_KEY_UPDATE);
            // Update cache
            $row       = parent::find($id);
            $cache     = Services::cache();
            $cache_key = $this->table . '-' . $id;
            $cache->delete($cache_key);
            $cache->save($cache_key, $row, self::HOURS_IN_SEC);
        }
        return $result;
    }

    /**
     * Delete the data (really delete from the database) and log
     * @param int|null $id
     * @param bool $purge
     * @return bool
     * @throws ReflectionException
     */
    public function delete($id = null, bool $purge = false): bool
    {
        $result = parent::delete($id, $purge);
        if ($result) {
            // Log
            $this->logAction($id, [], LogActivityModel::ACTIVITY_KEY_DELETE);
            // Delete cache
            $cache     = Services::cache();
            $cache_key = $this->table . '-' . $id;
            $cache->delete($cache_key);
        }
        return $result;
    }
}