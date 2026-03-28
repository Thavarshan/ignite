<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\FlagResource;
use App\Models\Flag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FlagController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $flags = Flag::query()
            ->with(['user', 'flaggable', 'resolver'])
            ->when($request->query('status') === 'resolved', fn ($q) => $q->whereNotNull('resolved_at'))
            ->when($request->query('status') === 'pending', fn ($q) => $q->whereNull('resolved_at'))
            ->when(! $request->query('status'), fn ($q) => $q->whereNull('resolved_at'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/Flags', [
            'flags' => FlagResource::collection($flags),
            'status' => $request->query('status', 'pending'),
        ]);
    }
}
