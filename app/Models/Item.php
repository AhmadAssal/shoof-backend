<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Watchlist;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    public function watchlist()
    {
        return $this->belongsToMany(Watchlist::class);
    }
}
