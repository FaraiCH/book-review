<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Driver\BulkWrite;


class Book extends Model
{
    use HasFactory;

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query){
        return $query->where('title', 'LIKE', '%qui%');
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null)
    {
        return $query->withCount(['reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)]);
    }

    public function scopeWithAvgRating(Builder $query, $from = null, $to = null)
    {
        return $query->withAvg(['reviews' => fn(Builder $q) =>  $this->dateRangeFilter($q, $from, $to) ], 'rating');
    }

    public function scopePopular(Builder $query, $from = null, $to = null)
    {
        return $query->withReviewsCount()
        ->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRates(Builder $query, $from = null, $to = null){
        return $query->withAvgRating()
        ->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReviews)
    {
       return $query->having('reviews_count', '>=', $minReviews);
    }

    public function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if($from && !$to){
            $query->where('create_at', '>=', $from);
        }elseif(!$from && $to){
            $query->where('create_at', '<=', $to);
        }elseif($from && $to){
            $query->whereBetween('created_at',[$from, $to]);
        }

    }

    public function scopePopularLastMonth(Builder $query)
    {
        return $query->popular(now()->subMonth(), now())->highestRates(now()->subMonth(), now())->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $query)
    {
        return $query->popular(now()->subMonth(24), now())->highestRates(now()->subMonth(24), now())->minReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query)
    {
        return $query->highestRates(now()->subMonth(), now())->popular(now()->subMonth(), now())->minReviews(5);
    }

    protected static function booted(){
        static::updated(fn(Book $book) => cache()->forget('book:'. $book->id));
        static::deleted(fn(Book $book) => cache()->forget('book:'. $book->id));
    }
}
