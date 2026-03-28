<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\FlagResource;
use App\Models\Flag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FlagController extends Controller
{
    /**
     * List all flags, optionally filtered by status.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $flags = Flag::query()
            ->when($request->query('status') === 'resolved', fn ($q) => $q->whereNotNull('resolved_at'))
            ->when($request->query('status') === 'pending', fn ($q) => $q->whereNull('resolved_at'))
            ->latest()
            ->paginate(20);

        return FlagResource::collection($flags);
    }

    /**
     * Mark a flag as resolved.
     */
    public function resolve(Request $request, Flag $flag): FlagResource
    {
        $flag->update([
            'resolved_at' => now(),
            'resolved_by' => $request->user()->id,
        ]);

        return FlagResource::make($flag->fresh());
    }
}
