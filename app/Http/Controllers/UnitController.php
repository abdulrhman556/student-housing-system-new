<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class UnitController extends Controller
{
    // ── عرض كل الوحدات في عقار معين (public) ──
    public function index($propertyId)
    {
        $property = Property::where('status', 'approved')->find($propertyId);

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'العقار مش موجود',
            ], 404);
        }

        $units = Unit::where('property_id', $propertyId)->get();

        return response()->json([
            'success' => true,
            'data'    => $units,
        ]);
    }

    // ── تفاصيل وحدة واحدة (public) ──
    public function show($id)
    {
        $unit = Unit::with('property')
            ->whereHas('property', function ($q) {
                $q->where('status', 'approved');
            })
            ->find($id);

        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'الوحدة مش موجودة',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $unit,
        ]);
    }

    // ── إضافة وحدة (owner) ──
    public function store(Request $request, $propertyId)
    {




        $property = Property::where('owner_id', $request->user()->id)->find($propertyId);

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'العقار مش موجود أو مش بتاعك',
            ], 404);
        }

        $request->validate([
            'unit_type'       => 'required|in:apartment,room,bed',
            'title'           => 'required|string|max:255',
            'price'           => 'required|numeric|min:0',
            'capacity'        => 'required|integer|min:1',
            'available_count' => 'required|integer|min:0',
            'gender'          => 'required|in:male,female',
        ]);

        $unit = Unit::create([
            'property_id'     => $propertyId,
            'unit_type'       => $request->unit_type,
            'title'           => $request->title,
            'price'           => $request->price,
            'capacity'        => $request->capacity,
            'available_count' => $request->available_count,
            'gender'          => $request->gender,
            'status'          => 'available',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الوحدة بنجاح',
            'data'    => $unit,
        ], 201);
    }

    // ── تعديل وحدة (owner) ──
    public function update(Request $request, $id)
    {
        $unit = Unit::whereHas('property', function ($q) use ($request) {
            $q->where('owner_id', $request->user()->id);
        })->find($id);

        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'الوحدة مش موجودة أو مش بتاعتك',
            ], 404);
        }

        $request->validate([
            'title'           => 'sometimes|string|max:255',
            'price'           => 'sometimes|numeric|min:0',
            'capacity'        => 'sometimes|integer|min:1',
            'available_count' => 'sometimes|integer|min:0',
            'gender'          => 'sometimes|in:male,female',
            'status'          => 'sometimes|in:available,reserved,occupied',
        ]);

        $unit->update($request->only([
            'title', 'price', 'capacity', 'available_count', 'gender', 'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'تم التعديل بنجاح',
            'data'    => $unit,
        ]);
    }

    // ── حذف وحدة (owner) ──
    public function destroy(Request $request, $id)
    {
        $unit = Unit::whereHas('property', function ($q) use ($request) {
            $q->where('owner_id', $request->user()->id);
        })->find($id);

        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'الوحدة مش موجودة أو مش بتاعتك',
            ], 404);
        }

        try {
            $unit->delete();
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف الوحدة لأن لها حجوزات مرتبطة بها.',
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم الحذف بنجاح',
        ]);
    }
}
