<?php

use App\Models\Channel;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

afterEach(function () {
    Schema::dropIfExists('slug_title_models');
    Schema::dropIfExists('slug_label_models');
});

test('it generates a slug from the name when creating a model', function () {
    $channel = Channel::query()->create([
        'name' => 'General Discussion',
    ]);

    expect($channel->slug)->toBe('general-discussion');
});

test('it appends a numeric suffix when the slug already exists', function () {
    Channel::query()->create([
        'name' => 'General Discussion',
    ]);

    $channel = Channel::query()->create([
        'name' => 'General Discussion',
    ]);

    expect($channel->slug)->toBe('general-discussion-2');
});

test('it preserves an explicitly provided slug when creating a model', function () {
    $channel = Channel::query()->create([
        'name' => 'General Discussion',
        'slug' => 'custom-channel-slug',
    ]);

    expect($channel->slug)->toBe('custom-channel-slug');
});

test('it regenerates the slug when the source attribute changes', function () {
    $channel = Channel::query()->create([
        'name' => 'General Discussion',
    ]);

    $channel->update([
        'name' => 'Announcements',
    ]);

    expect($channel->fresh()->slug)->toBe('announcements');
});

test('it does not override a manually updated slug', function () {
    $channel = Channel::query()->create([
        'name' => 'General Discussion',
    ]);

    $channel->update([
        'name' => 'Announcements',
        'slug' => 'custom-announcements',
    ]);

    expect($channel->fresh()->slug)->toBe('custom-announcements');
});

test('it generates a slug from the title when name is not available', function () {
    Schema::create('slug_title_models', function (Blueprint $table): void {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->timestamps();
    });

    $model = new class extends Model
    {
        use HasSlug;

        protected $table = 'slug_title_models';

        protected $fillable = ['title', 'slug'];
    };

    $record = $model->newQuery()->create([
        'title' => 'Release Notes',
    ]);

    expect($record->slug)->toBe('release-notes');
});

test('it generates a slug from the label when name and title are not available', function () {
    Schema::create('slug_label_models', function (Blueprint $table): void {
        $table->id();
        $table->string('label');
        $table->string('slug')->unique();
        $table->timestamps();
    });

    $model = new class extends Model
    {
        use HasSlug;

        protected $table = 'slug_label_models';

        protected $fillable = ['label', 'slug'];
    };

    $record = $model->newQuery()->create([
        'label' => 'Support Queue',
    ]);

    expect($record->slug)->toBe('support-queue');
});
