<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
            'images.*' => 'image|mimes:jpg,jpeg,png|max:8192',
        ], [
            'images.*.max' => 'حجم كل صورة يجب ألا يتجاوز 8 ميجابايت.',
            'images.*.uploaded' => 'تعذر رفع الصورة. يجب ألا يتجاوز حجمها 8 ميجابايت.',
        ]);

        $lastOrder = $property->images()->max('display_order') ?? 0;

        foreach ($request->file('images') as $index => $image) {

            // ── ضغط الصورة قبل الرفع لـ Cloudinary ──
            $compressedPath = $this->compressImage($image);

            $uploaded = cloudinary()->uploadApi()->upload(
                $compressedPath,
                ['folder' => 'properties']
            );

            // مسح الملف المؤقت بعد الرفع
            @unlink($compressedPath);

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

    // ── ضغط الصورة (Resize + Quality) وترجع مسار مؤقت جاهز للرفع ──
    private function compressImage($file, int $maxWidth = 1200, int $quality = 75): string
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file->getRealPath());

        // تصغير العرض لحد 1200 بكسل مع الحفاظ على النسبة، من غير تكبير للصور الأصغر
        $image->scaleDown(width: $maxWidth);

        $extension = strtolower($file->getClientOriginalExtension());

        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        if ($extension === 'png') {
            $tempPath = $tempDir . '/' . uniqid() . '.png';
            $image->toPng()->save($tempPath);
        } else {
            $tempPath = $tempDir . '/' . uniqid() . '.jpg';
            $image->toJpeg($quality)->save($tempPath);
        }

        return $tempPath;
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

        PropertyImage::where('property_id', $image->property_id)
            ->update(['is_cover' => false]);

        $image->update(['is_cover' => true]);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير الصورة الرئيسية بنجاح',
        ]);
    }
}
