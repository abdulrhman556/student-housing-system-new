<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;

class PropertyImageController extends Controller
{
    // ── إضافة صور لعقار موجود ──
    public function store(Request $request, $propertyId)
    {
        $property = Property::where('owner_id', auth()->id())->find($propertyId);

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'العقار مش موجود أو مش بتاعك',
            ], 404);
        }

        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $lastOrder = $property->images()->max('display_order') ?? 0;

        foreach ($request->file('images') as $index => $image) {
            $uploaded = cloudinary()->uploadApi()->upload(
                $image->getRealPath(),
                ['folder' => 'properties']
            );

            $property->images()->create([
                'image'         => $uploaded['secure_url'],
                'public_id'     => $uploaded['public_id'],
                'is_cover'      => $property->images()->count() === 0 && $index === 0,
                'display_order' => $lastOrder + $index + 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الصور بنجاح',
            'data'    => $property->load('images'),
        ]);
    }

    // ── حذف صورة معينة ──
    public function destroy($id)
    {
        $image = PropertyImage::whereHas('property', function ($q) {
            $q->where('owner_id', auth()->id());
        })->find($id);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'الصورة مش موجودة أو مش بتاعتك',
            ], 404);
        }

        if ($image->is_cover) {
            return response()->json([
                'success' => false,
                'message' => 'مش تقدر تحذف الصورة الرئيسية، غيّرها الأول',
            ], 422);
        }

        cloudinary()->uploadApi()->destroy($image->public_id);
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الصورة بنجاح',
        ]);
    }

    // ── تغيير الـ cover ──
    public function setCover($id)
    {
        $image = PropertyImage::whereHas('property', function ($q) {
            $q->where('owner_id', auth()->id());
        })->find($id);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'الصورة مش موجودة أو مش بتاعتك',
            ], 404);
        }

        // شيل الـ cover من الصورة القديمة
        PropertyImage::where('property_id', $image->property_id)
            ->update(['is_cover' => false]);

        // حط الـ cover على الصورة الجديدة
        $image->update(['is_cover' => true]);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير الصورة الرئيسية بنجاح',
        ]);
    }
}
