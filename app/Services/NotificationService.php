<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function send(array $data): Notification
    {
        return Notification::create([
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'title' => $data['title'],
            'body' => $data['body'],
            'data' => $data['data'] ?? null,
        ]);
    }

    public function index($userId)
    {
        return Notification::where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function unread($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->latest()
            ->get();
    }

    public function markAsRead(Notification $notification): Notification
    {
        $notification->update([
            'read_at' => now(),
        ]);

        return $notification;
    }

    public function delete(Notification $notification): bool
    {
        return $notification->delete();
    }
}
