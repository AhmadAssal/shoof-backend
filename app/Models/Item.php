<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Watchlist;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = ['name', 'is_movie', 'tmdb_id'];

    public function watchlists()
    {
        return $this->belongsToMany(Watchlist::class)->withPivot('item_order', 'rating');
    }
}
