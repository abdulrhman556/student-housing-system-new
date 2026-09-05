<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    // ── REGISTER ──
    public function register(Request $request)
    {
        $request->validate([
            'fname'    => 'required|string|max:255',
            'lname'    => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone'    => 'required|string',
            'gender'   => 'required|in:male,female',
            'role'     => 'required|in:student,owner',
            'national_id' => 'nullable|string|max:14',
            'national_id_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'university_id'     => 'required_if:role,student|nullable|exists:universities,id',

        ]);


    $user = User::create([
    'fname'             => $request->fname,
    'lname'             => $request->lname,
    'email'             => $request->email,
    'password'          => Hash::make($request->password),
    'phone'             => $request->phone,
    'gender'            => $request->gender,
    'role'              => $request->role,
    'status'            => $request->role === 'owner' ? 'pending' : 'active',
    'national_id'       => $request->national_id,
    'national_id_image' => $request->hasFile('national_id_image')
        ? $request->file('national_id_image')->store('national_ids', 'local')
        : null,

        'university_id'     => $request->role === 'student' ? $request->university_id : null,
]);
        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'تم التسجيل بنجاح. يرجى التحقق من البريد الإلكتروني',
            'user'    => $user,
        ], 201);
    }

    // ── USER LOGIN ──
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غلط',
            ], 401);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'الحساب مش مفعل',
            ], 403);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تفعيل البريد الإلكتروني أولاً',
            ], 403);
        }

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => [
    'id'   => $user->id,
    'name' => $user->full_name, // ✅ كدا هيجيب الاسم الأول والأخير مدمجين مع بعض بطريقة نضيفة
    'role' => $user->role,

            ],
        ]);
    }

    // ── ADMIN LOGIN ──
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غلط',
            ], 401);
        }

        $token = $admin->createToken('admin_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'admin'   => [
                'id'   => $admin->id,
                'name' => $admin->name,
            ],
        ]);
    }

    // ── LOGOUT ──
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج',
        ]);
    }

    // ── VERIFY EMAIL ──
    public function verifyEmail(Request $request, $id, $hash)
    {
        // The email link is opened through ngrok, which may change the scheme
        // seen by the local app. Validate the signed route path and parameters
        // (including expiration) without relying on the forwarded host/scheme.
        if (! $request->hasValidSignature(absolute: false)) {
            return response()->json([
                'success' => false,
                'message' => 'رابط التحقق غير صالح أو انتهت صلاحيته.',
            ], 403);
        }

        $user = User::find($id);
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود.',
            ], 404);
        }

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'success' => false,
                'message' => 'رابط التحقق غير صالح',
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'البريد الإلكتروني مفعل بالفعل',
            ]);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق من البريد الإلكتروني بنجاح',
        ]);
    }

    public function resendVerification(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'البريد الإلكتروني مفعل بالفعل',
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رابط التحقق مرة أخرى',
        ]);
    }

    // ── ME ──
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => $request->user(),
        ]);
    }
}
