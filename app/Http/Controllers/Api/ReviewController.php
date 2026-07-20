<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Property;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $reviewService)
    {
    }

    /**
     * Create a review for a completed booking.
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        try {
            $studentId = Auth::id() ?? 1;
            $review = $this->reviewService->create($request->validated(), $studentId);

            return response()->json([
                'success' => true,
                'message' => 'Review created successfully.',
                'data' => new ReviewResource($review),
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update a review owned by the current student.
     */
    public function update(Review $review, UpdateReviewRequest $request): JsonResponse
    {
        try {
            $studentId = Auth::id() ?? 1;
            $updatedReview = $this->reviewService->update($review, $studentId, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully.',
                'data' => new ReviewResource($updatedReview),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete a review owned by the current student.
     */
    public function destroy(Review $review): JsonResponse
    {
        try {
            $studentId = Auth::id() ?? 1;
            $this->reviewService->delete($review, $studentId);

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * List all reviews for a specific property.
     */
    public function index(Property $property): JsonResponse
    {
        try {
            $reviews = $this->reviewService->getPropertyReviews($property);

            return response()->json([
                'success' => true,
                'data' => ReviewResource::collection($reviews),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Show a single review.
     */
    public function show(Review $review): JsonResponse
    {
        try {
            $reviewDetails = $this->reviewService->show($review);

            return response()->json([
                'success' => true,
                'data' => new ReviewResource($reviewDetails),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
