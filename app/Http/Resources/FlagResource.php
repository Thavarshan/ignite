<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlagResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'flaggable_id' => $this->flaggable_id,
            'flaggable_type' => $this->flaggable_type,
            'reason' => $this->reason,
            'description' => $this->description,
            'resolved_at' => $this->resolved_at?->toIso8601String(),
            'resolved_by' => $this->resolved_by,
            'created_at' => $this->created_at->toIso8601String(),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]),
            'flaggable' => $this->whenLoaded('flaggable', fn () => [
                'id' => $this->flaggable->id,
                'type' => class_basename($this->flaggable_type),
                'title' => $this->flaggable->title ?? null,
                'body' => str($this->flaggable->body)->limit(200)->toString(),
                'slug' => $this->flaggable->slug ?? $this->flaggable->thread?->slug ?? null,
            ]),
            'resolver' => $this->whenLoaded('resolver', fn () => [
                'id' => $this->resolver->id,
                'name' => $this->resolver->name,
            ]),
        ];
    }
}
