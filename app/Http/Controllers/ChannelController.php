<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Channel;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): InertiaResponse
    {
        return Inertia::render('Channel/Index', [
            'channels' => Channel::with('threads')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Channel $channel
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Channel $channel
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Channel $channel)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Channel      $channel
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Channel $channel)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Channel $channel
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel)
    {
    }
}
