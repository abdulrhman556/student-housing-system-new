<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Favorite;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Review;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminDashboardService
{
    public function getStatistics(): array
    {
        $data = [];

        $data['users'] = [
            'students' => $this->countModel(User::class, ['role' => 'student']),
            'owners' => $this->countModel(User::class, ['role' => 'owner']),
            'admins' => $this->countModel('App\\Models\\Admin', []),
        ];

        $data['properties'] = [
            'total' => $this->countModel(Property::class),
            'approved' => $this->countModel(Property::class, ['status' => 'approved']),
            'pending' => $this->countModel(Property::class, ['status' => 'pending']),
            'rejected' => $this->countModel(Property::class, ['status' => 'rejected']),
        ];

        $data['units'] = [
            'total' => $this->countModel(Unit::class),
            'available' => $this->countModel(Unit::class, ['status' => 'available']),
            'occupied' => $this->countModel(Unit::class, ['status' => 'occupied']),
        ];

        $data['bookings'] = [
            'pending' => $this->countModel(Booking::class, ['status' => 'pending']),
            'availability_confirmed' => $this->countModel(Booking::class, ['status' => 'availability_confirmed']),
            'completed' => $this->countModel(Booking::class, ['status' => 'completed']),
            'cancelled' => $this->countModel(Booking::class, ['status' => 'cancelled']),
            'rejected' => $this->countModel(Booking::class, ['status' => 'rejected']),
        ];

        $data['payments'] = [
            'pending' => $this->countModel(Payment::class, ['status' => 'pending']),
            'verified' => $this->countModel(Payment::class, ['status' => 'verified']),
            'rejected' => $this->countModel(Payment::class, ['status' => 'rejected']),
            'total_revenue' => $this->sumModel(Payment::class, ['status' => 'verified'], 'amount'),
        ];

        $data['reviews'] = [
            'total' => $this->countModel(Review::class),
            'average_rating' => $this->averageModel(Review::class, 'rating'),
        ];

        $data['favorites'] = [
            'total' => $this->countModel(Favorite::class),
        ];

        return $data;
    }

    protected function countModel(string $model, array $conditions = []): int
    {
        if (! $this->modelExists($model)) {
            return 0;
        }

        $query = $this->resolveModel($model)::query();

        if ($conditions !== []) {
            $query->where($conditions);
        }

        return (int) $query->count();
    }

    protected function sumModel(string $model, array $conditions, string $column): float
    {
        if (! $this->modelExists($model)) {
            return 0;
        }

        $query = $this->resolveModel($model)::query();

        if ($conditions !== []) {
            $query->where($conditions);
        }

        return (float) $query->sum($column);
    }

    protected function averageModel(string $model, string $column): float
    {
        if (! $this->modelExists($model)) {
            return 0;
        }

        $query = $this->resolveModel($model)::query();

        $value = $query->avg($column);

        return $value === null ? 0.0 : round((float) $value, 1);
    }

    protected function modelExists(string $model): bool
    {
        if (! class_exists($model)) {
            return false;
        }

        return true;
    }

    protected function resolveModel(string $model)
    {
        return $model;
    }
}
