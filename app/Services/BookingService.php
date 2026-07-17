<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Unit;

class BookingService
{
    public function store(array $data)
    {
        // Temporary until Authentication is completed
        $data['student_id'] = 1;  //$data['student_id'] = auth()->id();

        // Get the selected unit
        $unit = Unit::findOrFail($data['unit_id']);

        // Check if the unit is available
        if ($unit->status !== 'available') {
            throw new \Exception('This unit is not available.');
        }

        // Check available places
        if ($unit->available_count <= 0) {
            throw new \Exception('No available places.');
        }

        // Check if the student already has an active booking
        $hasActiveBooking = Booking::where('student_id', $data['student_id'])
            ->whereIn('status', [
                'pending',
                'contacting_owner',
                'contacting_student',
            ])
            ->exists();

        if ($hasActiveBooking) {
            throw new \Exception('You already have an active booking.');
        }

        // Create booking
        $booking = Booking::create([
            'student_id'    => $data['student_id'],
            'unit_id'       => $data['unit_id'],
            'property_id'   => $unit->property_id,
            'status'        => 'pending',
            'booking_date'  => now()->toDateString(),
            'check_in_date' => $data['check_in_date'],
        ]);

        return $booking;

        



    }
}
