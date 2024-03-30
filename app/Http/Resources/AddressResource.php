<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'city_name' => $this->city?->name,
            'address' => $this->address,
            'bookmark' => $this->bookmark,
            'lat' => $this->lat,
            'long' => $this->long,
        ];
    }
}
