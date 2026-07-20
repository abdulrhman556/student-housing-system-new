<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'reference_number' => $this->reference_number,
            'payment_proof' => $this->payment_proof,
            'status' => $this->status,
            'verified_by' => $this->verified_by,
            'verified_at' => $this->verified_at,
            'created_at' => $this->created_at,
            'booking' => $this->whenLoaded('booking', function () {
                return [
                    'id' => $this->booking->id,
                    'status' => $this->booking->status,
                ];
            }),
        ];
    }
}
