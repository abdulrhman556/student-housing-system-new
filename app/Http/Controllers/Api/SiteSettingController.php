<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    public function show(): JsonResponse
    {
        $settings = Cache::remember('site_settings', now()->addHours(24), fn () => SiteSetting::first());

        return response()->json([
            'success' => true,
            'data' => [
                'contact_phone' => $settings?->contact_phone,
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'contact_phone' => 'required|string|max:20',
        ]);

        $settings = SiteSetting::first() ?? SiteSetting::create();
        $settings->update(['contact_phone' => $request->contact_phone]);

        Cache::forget('site_settings');

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث رقم التواصل بنجاح',
            'data' => $settings->fresh(),
        ]);
    }
}
