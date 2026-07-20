<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function __construct(protected AdminDashboardService $adminDashboardService)
    {
    }

    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Dashboard statistics loaded successfully.',
                'data' => $this->adminDashboardService->getStatistics(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to load dashboard statistics.',
            ], 500);
        }
    }
}
