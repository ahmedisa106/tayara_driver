<?php

namespace App\Http\Resources;

use App\Models\Customer;
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
            'id'             => $this->id,
            'products_count' => $this->products_count,
            'subtotal'       => $this->subtotal,
            'delivery_fee'   => $this->delivery_fee,
            'total'          => $this->total,
            'status'         => $this->status->toString(),
            'customer'       => new CustomerResource($this->customer),
            'address'        => new AddressResource($this->address),
            'branch'         => new BranchResource($this->branch),
        ];
    }
}
