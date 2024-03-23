<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'address'        => $this->address,
            'provider_name'       => $this->provider?->name,
            'lat'=>$this->lat,
            'long'=>$this->long,
            'provider_image' => $this->provider ? getFile($this->provider->image) : null,
        ];
    }
}
