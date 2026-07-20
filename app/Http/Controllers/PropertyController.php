<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PropertyController extends Controller
{
    // ===========================
    // عرض جميع العقارات
    // ===========================
    public function index(Request $request)
    {
        $query = Property::with([
            'owner',
            'coverImage',
            'amenities'
        ])->where('status', 'approved');

        // البحث
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhere('address', 'like', "%{$request->search}%");
            });
        }

        // المدينة
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // الجامعة
        if ($request->filled('university_id')) {
            $query->where('university_id', $request->university_id);
        }

        // الترتيب
        switch ($request->sort) {
            case 'most_viewed':
                $query->orderByDesc('view_count');
                break;

            case 'newest':
            default:
                $query->latest();
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate($request->per_page ?? 10)
        ]);
    }

    // ===========================
    // تفاصيل عقار
    // ===========================
    public function show($id)
    {
        $property = Property::with([
            'owner',
            'images',
            'coverImage',
            'amenities',
            'units',
            'reviews'
        ])
            ->where('status', 'approved')
            ->find($id);

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'العقار غير موجود'
            ], 404);
        }

        $property->increment('view_count');

        return response()->json([
            'success' => true,
            'data' => $property
        ]);
    }

    // ===========================
    // إضافة عقار جديد
    // ===========================
    public function store(Request $request)
    {
        $request->validate([

            'university_id' => 'required|exists:universities,id',

            'city_id' => 'required|exists:cities,id',

            'title' => 'required|string|max:255',

            'description' => 'required|string',

            'address' => 'required|string',

            'latitude' => 'required|numeric',

            'longitude' => 'required|numeric',

            'amenities' => 'nullable|array',

            'amenities.*' => 'exists:amenities,id',

            'images' => 'required|array|min:1',

            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',

        ]);

        $property = Property::create([

            'owner_id' => auth()->id(),

            'university_id' => $request->university_id,

            'city_id' => $request->city_id,

            'title' => $request->title,

            'description' => $request->description,

            'address' => $request->address,

            'latitude' => $request->latitude,

            'longitude' => $request->longitude,

            'status' => 'pending',

        ]);

        // الخدمات
        if ($request->filled('amenities')) {

            $property->amenities()->attach($request->amenities);

        }

        // الصور
        foreach ($request->file('images') as $index => $image) {
              $uploaded = cloudinary()
                  ->uploadApi()
                  ->upload(
                      $image->getRealPath(),
                      [
                          'folder' => 'properties',
                      ]
                  );

              $property->images()->create([
                'image'         => $uploaded['secure_url'],
                'public_id'     => $uploaded['public_id'],
                'is_cover'      => $index == 0,
                'display_order' => $index + 1,
]);


}



           return response()->json([

            'success' => true,

            'message' => 'تم إضافة العقار وهو الآن في انتظار موافقة الأدمن',

            'data' => $property->load([
                'images',
                'amenities'
            ])

        ], 201);
    }
    // ===========================
    // تعديل عقار
    // ===========================
    public function update(Request $request, $id)
    {


        $property = Property::where('owner_id', auth()->id())->find($id);

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'العقار غير موجود أو ليس من ممتلكاتك'
            ], 404);
        }


        $request->validate([

            'university_id' => 'sometimes|exists:universities,id',

            'city_id' => 'sometimes|exists:cities,id',

            'title' => 'sometimes|string|max:255',

            'description' => 'sometimes|string',

            'address' => 'sometimes|string',

            'latitude' => 'sometimes|numeric',

            'longitude' => 'sometimes|numeric',

            'amenities' => 'nullable|array',

            'amenities.*' => 'exists:amenities,id',

            'images' => 'nullable|array',

            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',

        ]);

        $property->update([

            'university_id' => $request->university_id ?? $property->university_id,

            'city_id' => $request->city_id ?? $property->city_id,

            'title' => $request->title ?? $property->title,

            'description' => $request->description ?? $property->description,

            'address' => $request->address ?? $property->address,

            'latitude' => $request->latitude ?? $property->latitude,

            'longitude' => $request->longitude ?? $property->longitude,

            // أي تعديل يحتاج مراجعة الأدمن مرة أخرى
            'status' => 'pending',

            'rejection_reason' => null,

        ]);

        // تحديث الخدمات
        if ($request->has('amenities')) {

            $property->amenities()->sync($request->amenities);

        }

            if ($request->hasFile('images')) {
                foreach ($property->images as $image) {
               cloudinary()
              ->uploadApi()
            ->destroy($image->public_id);
                }

                $property->images()->delete();

                foreach ($request->file('images') as $index => $image) {
                  $uploaded = cloudinary()
                 ->uploadApi()
                 ->upload(
                     $image->getRealPath(),
                     [
                         'folder' => 'properties',
                     ]
                 );

               $property->images()->create([
                 'image'         => $uploaded['secure_url'],
                 'public_id'     => $uploaded['public_id'],
                 'is_cover'      => $index == 0,
                 'display_order' => $index + 1,
]);
                }
            }


                    return response()->json([

            'success' => true,

            'message' => 'تم تعديل العقار وهو الآن في انتظار مراجعة الأدمن',

            'data' => $property->load([
                'images',
                'amenities'
            ])

        ]);

        $property->refresh();
    }


    // ===========================
    // حذف عقار
    // ===========================

public function destroy($id)
{
    $property = Property::where('owner_id', auth()->id())->find($id);

    if (!$property) {
        return response()->json([
            'success' => false,
            'message' => 'العقار غير موجود أو ليس من ممتلكاتك'
        ], 404);
    }

        foreach ($property->images as $image) {
                 cloudinary()
                ->uploadApi()
                ->destroy($image->public_id);
                    }

        $property->images()->delete();
        $property->delete();


        return response()->json([
                'success' => true,
                'message' => 'تم حذف العقار بنجاح'
            ]);
}



    // ===========================
    // موافقة الأدمن
    // ===========================
    public function approve($id)
    {
        $property = Property::find($id);

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'العقار غير موجود'
            ], 404);
        }

        $property->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم اعتماد العقار بنجاح'
        ]);
    }

    // ===========================
    // رفض العقار
    // ===========================
public function reject(Request $request, $id)
{
    $request->validate([
        'rejection_reason' => 'required|string'
    ]);

    $property = Property::find($id);

    if (!$property) {
        return response()->json([
            'success' => false,
            'message' => 'العقار غير موجود'
        ], 404);
    }

    $property->update([
        'status' => 'rejected',
        'rejection_reason' => $request->rejection_reason,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'تم رفض العقار'
    ]);
}


    // ===========================
    // عقارات صاحب السكن
    // ===========================
    public function myProperties(Request $request)
    {
        $properties = Property::with([
            'coverImage',
            'images',
            'amenities',
            'units'
        ])
            ->where('owner_id', auth()->id())
            ->latest()
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => $properties
        ]);
    }
}
