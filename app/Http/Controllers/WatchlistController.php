<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use App\Http\Requests\WatchlistRequest;
use App\Models\Item;

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
        if ($request->user()->id != $request->user_id) {
            return response()->json(["error" => 'unauthorized'], 403);
        }
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
    public function show(Watchlist $watchlist, Request $request)
    {
        if ($request->user()->id != $watchlist->user_id) {
            return response()->json(["error" => 'unauthorized'], 403);
        }
        $watchlist['items'] = $watchlist->items;
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
        if ($request->user()->id != $watchlist->user_id) {
            return response()->json(["error" => 'unauthorized'], 403);
        }
        $watchlist->update($request->validated());
        return response()->json(['watchlist' => $watchlist], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Watchlist  $watchlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Watchlist $watchlist)
    {
        if ($request->user()->id != $watchlist->user_id) {
            return response()->json(["error" => 'unauthorized'], 403);
        }
        $watchlist->delete();
        return response()->json('', 204);
    }

    public function addItem(Request $request)
    {

        $watchlist = Watchlist::find($request->watchlist_id);
        if ($request->user()->id != $watchlist->user_id) {
            return response()->json(["error" => 'unauthorized'], 403);
        }
        $item = Item::firstOrCreate(
            ['tmdb_id' => $request->tmdb_id],
            [
                'name' => $request->name,
                'is_movie' => $request->is_movie,
                'tmdb_id' => $request->tmdb_id
            ]
        );
        if (is_null($item)) return response(['error' => "item not found"], 404);
        if ($watchlist->items()->where('tmdb_id', $request->tmdb_id)->first()) {
            return  response()->json(["error" => 'Item already exists in watchlist'], 400);
        }

        $last_item = $watchlist->items()->where('watchlist_id', $request->watchlist_id)->orderBy('item_order', 'desc')->first();

        $watchlist->items()->attach($item->id, [
            'rating' => isset($request->rating) || NULL,
            'item_order' => $last_item->pivot->item_order + 1
        ]);
        $watchlist['items'] = $watchlist->items;
        return response(['watchlist' => $watchlist], 200);
    }
    public function removeItem(Request $request)
    {
        $watchlist = Watchlist::find($request->watchlist_id);
        if ($request->user()->id != $watchlist->user_id) {
            return response()->json(["error" => 'unauthorized'], 403);
        }
        $item = $watchlist->items()->where('tmdb_id', $request->tmdb_id)->first();
        if (!$item) {
            return response()->json(['error' => 'This item is not associated with this watchlist']);
        }
        $item->watchlists()->detach($watchlist);
        return response()->json(['watchlist' => $watchlist], 200);
    }

    public function editItems(Request $request)
    {
        $items = json_decode($request->items, true);
        $watchlist = Watchlist::find($request->watchlist_id);
        foreach ($items as $item) {
            $watchlist->items()->updateExistingPivot($item["item_id"], $item);
        }
        return response()->json(['watchlist' => $watchlist->with('items')->get()], 200);
    }
}
