<?php

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Channel::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('body');
            $table->boolean('is_ticket')->default(false);
            $table->string('status')->default(Status::OPEN->value);
            $table->string('priority')->default(Priority::LOW->value);
            $table->foreignIdFor(User::class, 'assigned_to_id')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedInteger('replies_count')->default(0);
            $table->timestamp('last_reply_at')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('pinned_at')->nullable();
            $table->timestamp('flagged_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threads');
    }
};
