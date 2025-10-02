<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThreadResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'user_id' => $this->user_id,
            'channel_id' => $this->channel_id,
            'channel' => new ChannelResource($this->whenLoaded('channel')),
            'author' => new UserResource($this->whenLoaded('author')),
            'replies' => ReplyResource::collection($this->whenLoaded('replies')),
            'replies_count' => $this->replies_count,
            'locked' => $this->locked,
            'locked_at' => $this->locked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
