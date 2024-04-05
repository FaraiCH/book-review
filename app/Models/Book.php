<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Book extends Model
{
    use HasFactory;

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query){
        return $query->where('title', 'LIKE', '%qui%');
    }

    public function scopePopular(Builder $query, $from = null, $to = null){
        return $query->withCount(['reviews' => function(Builder $q) use($from, $to){
            if($from && !$to){
                $q->where('create_at', '>=', $from);
            }elseif(!$from && $to){
                $q->where('create_at', '<=', $to);
            }elseif($from && $to){
                $q->whereBetween('created_at',[$from, $to]);
            }
        }])
        ->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRates(Builder $query){
        return $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
    }
}
