<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ReviewService
{
    /**
     * Create a review only when the booking belongs to the student and is completed.
     */
    public function create(array $data, int $studentId): Review
    {
        $booking = Booking::query()->findOrFail($data['booking_id']);

        if ((int) $booking->student_id !== $studentId) {
            throw new RuntimeException('You are not authorized to review this booking.');
        }

        if ($booking->status !== 'completed') {
            throw new RuntimeException('A review can only be created for completed bookings.');
        }

        $existingReview = Review::query()->where('booking_id', $booking->id)->exists();
        if ($existingReview) {
            throw new RuntimeException('A review already exists for this booking.');
        }

        return DB::transaction(function () use ($booking, $data, $studentId): Review {
            return Review::query()->create([
                'booking_id' => $booking->id,
                'student_id' => $studentId,
                'property_id' => $booking->property_id,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]);
        });
    }

    /**
     * Update only the review owned by the current student.
     */
    public function update(Review $review, int $studentId, array $data): Review
    {
        if ((int) $review->student_id !== $studentId) {
            throw new RuntimeException('You are not allowed to edit this review.');
        }

        return DB::transaction(function () use ($review, $data): Review {
            $review->update([
                'rating' => $data['rating'] ?? $review->rating,
                'comment' => $data['comment'] ?? $review->comment,
            ]);

            return $review->fresh(['booking', 'student', 'property']);
        });
    }

    /**
     * Delete only the review owned by the current student.
     */
    public function delete(Review $review, int $studentId): bool
    {
        if ((int) $review->student_id !== $studentId) {
            throw new RuntimeException('You are not allowed to delete this review.');
        }

        return DB::transaction(function () use ($review): bool {
            return (bool) $review->delete();
        });
    }

    /**
     * View all reviews for a property.
     */
    public function getPropertyReviews(Property $property)
    {
        return $property->reviews()->with(['student', 'booking'])->latest()->get();
    }

    /**
     * View a single review.
     */
    public function show(Review $review): Review
    {
        return $review->load(['student', 'booking', 'property']);
    }
}
