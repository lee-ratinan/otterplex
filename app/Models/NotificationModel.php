<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class NotificationModel extends Model
{
    protected $table            = 'notification_master';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'user_id',
        'notification_type',
        'notification_message',
        'notification_link',
        'is_read',
        'created_at',
        'updated_at'
    ];

    /**
     * Format each notification row for insertion
     * @param int $user_id
     * @param string $notification_type
     * @param string $notification_message
     * @param string $notification_link
     * @return array
     */
    public function formatNotificationRow(int $user_id, string $notification_type, string $notification_message, string $notification_link): array
    {
        return [
            'user_id'              => $user_id,
            'notification_type'    => $notification_type,
            'notification_message' => $notification_message,
            'notification_link'    => $notification_link,
            'is_read'              => 'N',
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Custom method to create notifications and automatically clear relevant caches.
     * Supports both a single notification array or a multidimensional array for batch inserts.
     * @param array $data
     * @return bool
     */
    public function createAndClearCache(array $data): bool
    {
        try {
            // 1. Determine if this is a single notification or a batch
            // If the first element isn't an array, it's a single notification
            $isBatch = isset($data[0]) && is_array($data[0]);
            if ($isBatch) {
                // Perform an optimized batch insert
                $this->insertBatch($data);
                // Extract all unique user IDs from the batch to clear their caches
                $userIds = array_unique(array_column($data, 'user_id'));
                foreach ($userIds as $userId) {
                    cache()->delete("user_notifications_{$userId}");
                }
            } else {
                // Perform a single insert
                $this->insert($data);
                // Clear cache for this single user
                $userId = $data['user_id'];
                cache()->delete("user_notifications_{$userId}");
            }
            return true;
        } catch (Exception $e) {
            log_message('error', 'Error creating notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get unread notifications for a specific user
     * @param int $user_id
     * @return int|string
     */
    public function getUnreadCount(int $user_id): int|string
    {
        return $this->where(['user_id' => $user_id, 'is_read' => 0])->countAllResults();
    }

    /**
     * Get recent notifications for the dropdown list
     * @param int $user_id
     * @param int $limit
     * @return array
     */
    public function getNotificationsList(int $user_id, int $limit = 5): array
    {
        return $this->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}