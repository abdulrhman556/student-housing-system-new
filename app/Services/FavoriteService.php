<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FavoriteService
{
    /**
     * Add property to favorites.
     */
    public function store(array $data): Favorite
    {
        $studentId = auth()->id();

        if (!$studentId) {
            throw new RuntimeException('Unauthenticated.');
        }

        Property::findOrFail($data['property_id']);

        $exists = Favorite::query()
            ->where('student_id', $studentId)
            ->where('property_id', $data['property_id'])
            ->exists();

        if ($exists) {
            throw new RuntimeException(
                'Property already exists in favorites.'
            );
        }

        return DB::transaction(function () use ($studentId, $data): Favorite {

            $favorite = Favorite::query()->create([
                'student_id' => $studentId,
                'property_id' => $data['property_id'],
            ]);

            return $favorite->load([
                'property.coverImage',
                'property.owner',
            ]);
        });
    }

    /**
     * List current student's favorites.
     */
    public function index()
    {
        $studentId = auth()->id();

        if (!$studentId) {
            throw new RuntimeException('Unauthenticated.');
        }

        return Favorite::query()
            ->where('student_id', $studentId)
            ->with([
                'property.coverImage',
                'property.owner',
            ])
            ->latest()
            ->paginate(20);
    }

    /**
     * Remove favorite.
     */
    public function destroy(Favorite $favorite): bool
    {
        $studentId = auth()->id();

        if (!$studentId) {
            throw new RuntimeException('Unauthenticated.');
        }

        if ((int) $favorite->student_id !== (int) $studentId) {
            throw new RuntimeException(
                'You are not allowed to delete this favorite.'
            );
        }

        return DB::transaction(function () use ($favorite): bool {
            return (bool) $favorite->delete();
        });
    }
}
