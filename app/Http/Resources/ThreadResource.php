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
            'is_ticket' => $this->is_ticket,
            'status' => $this->status,
            'priority' => $this->priority,
            'assigned_to_id' => $this->assigned_to_id,
            'assigned_to' => new UserResource($this->whenLoaded('assignedTo')),
            'user' => new UserResource($this->whenLoaded('user')),
            'user_id' => $this->user_id,
            'channel_id' => $this->channel_id,
            'channel' => new ChannelResource($this->whenLoaded('channel')),
            'views' => $this->views,
            'replies_count' => $this->replies_count,
            'replies' => ReplyResource::collection($this->whenLoaded('replies')),
            'last_reply_at' => $this->last_reply_at,
            'locked_at' => $this->locked_at,
            'pinned_at' => $this->pinned_at,
            'flagged_at' => $this->flagged_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
