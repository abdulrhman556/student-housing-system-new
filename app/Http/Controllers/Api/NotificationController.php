<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function index()
    {
        $adminId = auth()->id() ?? 1;

        return NotificationResource::collection(
            $this->notificationService->index($adminId)
        );
    }

    public function unread()
    {
        $adminId = auth()->id() ?? 1;

        return NotificationResource::collection(
            $this->notificationService->unread($adminId)
        );
    }

public function markAsRead(Notification $notification): JsonResponse
{
    if ($notification->user_id != auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    $notification = $this->notificationService->markAsRead($notification);

    return response()->json([
        'success' => true,
        'message' => 'Notification marked as read.',
        'data' => new NotificationResource($notification),
    ]);
}

public function destroy(Notification $notification): JsonResponse
{
    if ($notification->user_id != auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    $this->notificationService->delete($notification);

    return response()->json([
        'success' => true,
        'message' => 'Notification deleted successfully.',
    ]);
}}
