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
            'channel_id' => $this->channel_id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'locked' => $this->locked,
            'pinned' => $this->pinned,
            'replies_count' => $this->replies_count,
            'best_reply_id' => $this->best_reply_id,
            'likes_count' => $this->likes_count,
            'visits' => $this->visits,
            'last_posted_at' => $this->last_posted_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'channel' => $this->whenLoaded('channel', fn () => [
                'id' => $this->channel?->id,
                'name' => $this->channel?->name,
                'slug' => $this->channel?->slug,
            ]),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ]),
            'best_reply' => $this->whenLoaded('bestReply'),
        ];
    }
}
