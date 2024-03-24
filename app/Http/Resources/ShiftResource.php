<?php

namespace App\Http\Resources;

use App\Http\Resources\orders\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_at' => $this->start_at->isoFormat('dddd LL hh:mm A'),
            'end_at' => $this->end_at?->isoFormat('dddd LL hh:mm A'),
            'total' => (double)$this->orders_sum_driver_ratio ?? 0,
            'total_orders' => (int)$this->orders_count,
            'orders' => OrderResource::collection($this->orders)
        ];
    }
}
