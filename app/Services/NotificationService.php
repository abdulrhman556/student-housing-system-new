<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use RuntimeException;

class NotificationService
{
    /**
     * Create a notification.
     */
    public function send(array $data): Notification
    {
        if (empty($data['user_id'])) {
            throw new InvalidArgumentException('User ID is required.');
        }

        if (empty($data['type'])) {
            throw new InvalidArgumentException('Notification type is required.');
        }

        if (empty($data['title'])) {
            throw new InvalidArgumentException('Notification title is required.');
        }

        if (empty($data['body'])) {
            throw new InvalidArgumentException('Notification body is required.');
        }

        return Notification::create([
            'user_id' => $data['user_id'],
            'type'    => $data['type'],
            'title'   => $data['title'],
            'body'    => $data['body'],
            'data'    => $data['data'] ?? null,
        ]);
    }

    /**
     * Get all notifications for authenticated user.
     */
    public function index(): LengthAwarePaginator
    {
        return Notification::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(20);
    }

    /**
     * Get unread notifications.
     */
    public function unread(): LengthAwarePaginator
    {
        return Notification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->latest()
            ->paginate(20);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification): Notification
    {
        if ((int) $notification->user_id !== (int) Auth::id()) {
            throw new RuntimeException(
                'You are not authorized to access this notification.'
            );
        }

        if ($notification->read_at === null) {
            $notification->update([
                'read_at' => now(),
            ]);
        }

        return $notification->fresh();
    }

    /**
     * Delete notification.
     */
    public function delete(Notification $notification): bool
    {
        if ((int) $notification->user_id !== (int) Auth::id()) {
            throw new RuntimeException(
                'You are not authorized to delete this notification.'
            );
        }

        return (bool) $notification->delete();
    }
}
