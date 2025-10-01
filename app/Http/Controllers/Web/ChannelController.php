<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;
use Inertia\Inertia;
use Inertia\Response;

class ChannelController extends Controller
{
    public function show(Channel $channel): Response
    {
        return Inertia::render('channels/Show', [
            'channel' => new ChannelResource($channel),
        ]);
    }
}
