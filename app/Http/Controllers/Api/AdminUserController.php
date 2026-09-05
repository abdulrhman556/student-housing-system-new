<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    /**
     * List accounts for the administration panel without exposing them
     * through public endpoints.
     */
    public function students(Request $request): JsonResponse
    {
        return $this->usersByRole($request, 'student');
    }

    public function owners(Request $request): JsonResponse
    {
        return $this->usersByRole($request, 'owner');
    }

    private function usersByRole(Request $request, string $role): JsonResponse
    {
        $perPage = min(max((int) $request->input('per_page', 50), 1), 100);
        $query = User::where('role', $role)->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate($perPage),
        ]);
    }

    /**
     * عرض كل حسابات الـ Owners في انتظار موافقة الأدمن
     */
    public function pendingOwners(Request $request): JsonResponse
    {
        $owners = User::where('role', 'owner')
            ->where('status', 'pending')
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $owners,
        ]);
    }

    /**
     * تفعيل حساب Owner عشان يقدر يسجل دخول ويضيف عقارات
     */
    public function approveOwner($id): JsonResponse
    {
        $user = User::where('role', 'owner')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب غير موجود',
            ], 404);
        }

        if ($user->status === 'active') {
            return response()->json([
                'success' => true,
                'message' => 'الحساب مفعل بالفعل',
            ]);
        }

        $user->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'تم تفعيل حساب صاحب السكن بنجاح',
            'data' => $user->fresh(),
        ]);
    }

    /**
     * رفض/حظر حساب Owner (مع سبب اختياري)
     */
    public function blockOwner(Request $request, $id): JsonResponse
    {
        $user = User::where('role', 'owner')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب غير موجود',
            ], 404);
        }

        $user->update(['status' => 'blocked']);

        return response()->json([
            'success' => true,
            'message' => 'تم حظر الحساب',
            'data' => $user->fresh(),
        ]);
    }

    public function nationalIdImage($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب غير موجود',
            ], 404);
        }

        if (! $user->national_id_image || ! Storage::disk('local')->exists($user->national_id_image)) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد صورة رقم قومي مرفوعة',
            ], 404);
        }

        return Storage::disk('local')->response($user->national_id_image);
    }
}
