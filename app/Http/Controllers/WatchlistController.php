<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use App\Http\Requests\WatchlistRequest;

class WatchlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['watchlists' => Watchlist::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WatchlistRequest $request)
    {
        $validated_data = (object) $request->validated();
        $watchlist = Watchlist::create(['name' => $validated_data->name, 'user_id' => $validated_data->user_id]);
        return response()->json(['watchlist' => $watchlist], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Watchlist  $watchlist
     * @return \Illuminate\Http\Response
     */
    public function show(Watchlist $watchlist)
    {
        return response()->json(["watchlist" => $watchlist], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Watchlist  $watchlist
     * @return \Illuminate\Http\Response
     */
    public function update(WatchlistRequest $request, Watchlist $watchlist)
    {
        $watchlist->update($request->validated());
        return response()->json(['watchlist' => $watchlist], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Watchlist  $watchlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Watchlist $watchlist)
    {
        $watchlist->delete();
        return response()->json('', 204);
    }
}
