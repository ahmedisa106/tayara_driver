<?php

namespace App\Http\Resources\orders;

use App\Enums\OrderStatus;
use App\Http\Resources\AddressResource;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_code' => $this->order_code,
            'products_count' => $this->products_count ?? 0,
            'subtotal' => (double)$this->subtotal,
            'status' => $this->status->toString(),
            'created_at' => $this->created_at->format('Y-m-d H:i A'),
            'provider_id' => $this->provider_id,
            'provider_name' => $this->provider?->name ?? null,
            'provider_image' => $this->provider ? getFile($this->provider->image) : null,
            'attached' => (bool)$this->driver_attached_order,
            'attached_from_branch' => (bool)$this->driver_attached_order_from_provider,
            'completed' => $this->status == OrderStatus::Complete,
            'cancelled' => $this->status == OrderStatus::Cancelled,
        ];
    }
}
