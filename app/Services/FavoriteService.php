<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FavoriteService
{
    public function store(array $data): Favorite
    {
        $studentId = auth()->id() ?? 1;

        Property::findOrFail($data['property_id']);

        $exists = Favorite::where('student_id', $studentId)
            ->where('property_id', $data['property_id'])
            ->exists();

        if ($exists) {
            throw new RuntimeException('Property already exists in favorites.');
        }

        return DB::transaction(function () use ($studentId, $data) {

            return Favorite::create([
                'student_id' => $studentId,
                'property_id' => $data['property_id'],
            ]);

        });
    }

    public function index()
    {
        $studentId = auth()->id() ?? 1;

        return Favorite::with('property')
            ->where('student_id', $studentId)
            ->latest()
            ->get();
    }

    public function destroy(Favorite $favorite): bool
    {
        return $favorite->delete();
    }
}
