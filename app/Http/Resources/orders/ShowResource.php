<?php

namespace App\Http\Resources\orders;

use App\Enums\OrderStatus;
use App\Http\Resources\AddressResource;
use App\Http\Resources\orders\OrderProductResource;
use App\Http\Resources\BranchResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'order_code' => sprintf('%06d', $this->order_code),
            'provider_id' => $this->provider_id,
            'products_count' => (int)$this->products_count,
            'subtotal' => !is_null($this->provider_id) ? (double)$this->subtotal : (double)$this->total,
            'delivery_fee' => (double)$this->delivery_fee,
            'total' => !is_null($this->provider_id) ? (double)$this->total : (double)($this->delivery_fee + $this->total),
            'discount' => (double)$this->discount ?? 0,
            'status' => $this->status->toString(),
            'details' => $this->note,
            'created_at' => $this->created_at->isoFormat('dddd LL hh:mm A'),
            'attached' => (bool)$this->driver_attached_order,
            'attached_from_branch' => (bool)$this->driver_attached_order_from_provider,
            'completed' => $this->status == OrderStatus::Complete,
            'cancelled' => $this->status == OrderStatus::Cancelled,
            'customer' => new CustomerResource($this->customer),
            'address' => new AddressResource($this->address),
            'branch' => new BranchResource($this->branch),
            'products' => null,
            'product_image' => $this->image ? getFile($this->image) : null,
        ];

        if ($this->products_count > 0) {
            $data['products'] = OrderProductResource::collection($this->products);
        }

        return $data;
    }
}
