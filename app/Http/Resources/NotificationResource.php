<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'title' => @$this->data['title'] ?? "",
            'body' => @$this->data['body'] ? ($request->segment(4) != null ? @$this->data['body'] : Str::limit(@$this->data['body'], 50)) : "",
            'created_at' => Carbon::parse($this->created_at)->longRelativeToNowDiffForHumans(),
            'is_read' => !is_null($this->read_at),
            'image' => @$this->data['icon']
        ];
    }
}
