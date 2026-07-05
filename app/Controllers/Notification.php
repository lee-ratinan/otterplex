<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\HTTP\ResponseInterface;

class Notification extends BaseController
{
    protected NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Retrieve notifications
     * @return ResponseInterface
     */
    public function fetch(): ResponseInterface
    {
        $user_id  = session()->get('user_id') ?? 0;
        if (0 < $user_id) {
            try {
                $cacheKey = "user_notifications_{$user_id}";
                if (!$data = cache($cacheKey)) {
                    $unreadCount = $this->notificationModel->getUnreadCount($user_id);
                    $list        = $this->notificationModel->getNotificationsList($user_id);
                    $data        = [
                        'unread_count'  => $unreadCount,
                        'notifications' => $list
                    ];
                    // Save to the native cache for 60 seconds (1 minute)
                    cache()->save($cacheKey, $data, 60);
                }
                return $this->response->setJSON($data);
            } catch (\Exception $e) {
                log_message('error', 'There is something wrong while getting notifications: ' . $e->getMessage());
                return $this->response->setJSON([
                    'unread_count'  => 0,
                    'notifications' => []
                ]);
            }
        } else {
            return $this->response->setJSON([
                'unread_count'  => 0,
                'notifications' => []
            ]);
        }
    }

    /**
     * Mark as read (Invalidates the Cache)
     * @param int $id
     * @return ResponseInterface
     */
    public function markAsRead(int $id): ResponseInterface
    {
        $user_id = session()->get('user_id');
        try {
            $notification = $this->notificationModel->where([
                'id'      => $id,
                'user_id' => $user_id
            ])->first();
            if ($notification) {
                $this->notificationModel->update($id, ['is_read' => 1]);
                cache()->delete("user_notifications_{$user_id}");
                return $this->response->setJSON(['status' => 'success']);
            }
            return $this->response->setJSON(['status' => 'error', 'message' => 'Not found']);
        } catch (\Exception $e) {
            log_message('error', 'There is something wrong while marking notification as read: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Something went wrong']);
        }
    }
}