<?php

namespace App\Http\Resources\orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $options = [];

        if ($this->pivot->option_values) {
            foreach (json_decode($this->pivot->option_values, true) as $option) {
                $options[] = /*$option['option_text'] . ' : ' .*/
                    $option['option_value_text'];
            }

            $options = implode(' + ', $options);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => (double)$this->pivot->price,
            'currency' => config('app.currency'),
            'quantity' => (int)$this->pivot->quantity,
            'options' => !empty($options) ? $options : null,
            'note' => $this->pivot->note,
            'image' => $this->image ? getFile($this->image) : null,
        ];
    }
}
