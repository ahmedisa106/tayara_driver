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
            'total' => (double)$this->driver_salary ?? 0,
            'office_salary' => (double)($this->orders_sum_delivery_fee - $this->driver_salary) ?? 0,
            'custody' => (double)$this->custody ?? 0,
            'total_orders' => (int)$this->orders_count,
            'orders' => $this->whenLoaded('orders', fn() => OrderResource::collection($this->orders)),
        ];
    }
}
