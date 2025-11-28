<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'experience' => [
                'id' => $this->experience->id,
                'title' => $this->experience->title,
                'price' => $this->experience->price,
            ],
            'traveler' => [
                'id' => $this->traveler->id,
                'full_name' => $this->traveler->full_name,
            ],
            'start_at' => $this->start_at,
            'guests' => $this->guests,
            'subtotal' => $this->subtotal,
            'platform_fee' => $this->platform_fee,
            'total' => $this->total,
            'currency' => $this->currency,
            'status' => $this->status,
            'payment' => [
                'provider' => $this->payment_provider,
                'provider_id' => $this->payment_provider_id
            ],
            'created_at' => $this->created_at,
        ];
    }
}
