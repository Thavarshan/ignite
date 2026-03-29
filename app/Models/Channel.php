<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Policies\ChannelPolicy;
use Database\Factories\ChannelFactory;
use Filterable\Traits\Filterable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(ChannelPolicy::class)]
class Channel extends Model
{
    /** @use HasFactory<ChannelFactory> */
    use Filterable, HasFactory, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get all threads associated with the channel.
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }
}
