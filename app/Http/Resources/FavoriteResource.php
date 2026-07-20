<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'property' => [

                'id' => $this->property->id,

                'title' => $this->property->title,

                'address' => $this->property->address,

                'status' => $this->property->status,

            ],

            'created_at' => $this->created_at,

        ];
    }
}
