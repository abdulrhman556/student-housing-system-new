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
use RuntimeException;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {
    }

    /**
     * Create a review.
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        try {

            $studentId = Auth::id();

            if (!$studentId) {
                throw new RuntimeException('Unauthenticated.');
            }

            $review = $this->reviewService->create(
                $request->validated(),
                $studentId
            );

            return response()->json([
                'success' => true,
                'message' => 'Review created successfully.',
                'data' => new ReviewResource($review),
            ], 201);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * Update review.
     */
    public function update(
        Review $review,
        UpdateReviewRequest $request
    ): JsonResponse
    {
        try {

            $studentId = Auth::id();

            if (!$studentId) {
                throw new RuntimeException('Unauthenticated.');
            }

            $review = $this->reviewService->update(
                $review,
                $studentId,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully.',
                'data' => new ReviewResource($review),
            ]);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * Delete review.
     */
    public function destroy(Review $review): JsonResponse
    {
        try {

            $studentId = Auth::id();

            if (!$studentId) {
                throw new RuntimeException('Unauthenticated.');
            }

            $this->reviewService->delete(
                $review,
                $studentId
            );

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully.',
            ]);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * Property reviews.
     */
    public function index(Property $property): JsonResponse
    {
        try {

            $reviews = $this->reviewService->getPropertyReviews($property);

            return response()->json([
                'success' => true,
                'data' => ReviewResource::collection($reviews),
            ]);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * Show review.
     */
    public function show(Review $review): JsonResponse
    {
        try {

            $review = $this->reviewService->show($review);

            return response()->json([
                'success' => true,
                'data' => new ReviewResource($review),
            ]);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        }
    }
}
