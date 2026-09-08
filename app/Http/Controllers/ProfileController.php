<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // ── تعديل البيانات ──
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'fname'         => 'sometimes|string|max:255',
            'lname'         => 'sometimes|string|max:255',
        ]);

        // Phone, email, gender, national ID and its image are verified account
        // data, so this endpoint only permits a user to change their name.
        $data = $request->only(['fname', 'lname']);


        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث البيانات بنجاح',
            'data'    => $user->fresh(),
        ]);
    }

    // ── تغيير كلمة السر ──
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة السر الحالية غلط',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير كلمة السر بنجاح',
        ]);
    }

    public function nationalIdImage(Request $request)
    {
        $user = $request->user();

        if (! $user->national_id_image || ! Storage::disk('local')->exists($user->national_id_image)) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد صورة رقم قومي مرفوعة',
            ], 404);
        }

        return Storage::disk('local')->response($user->national_id_image);
    }
}
