<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        "title" , 
        "author"
    ];

    public function reviews(){
        return $this->hasMany(Review::class);
    }
    public function scopeTitle($query , $title){
        return $query->where('title' , "LIKE" ,"%".$title. "%");
    }
    public function scopePopular( $query  , $from = null , $to = null) {
        return $query->withCount([
            "reviews" => $this->withRangeReviews($query ,$from, $to)
        ])->orderBy("reviews_count" , "desc");
    }
    public function scopeHighestRated($query ){
        return $query->withAvg("reviews" , "rating")->orderBy("reviews_avg_rating" , "desc");
    }
    private function withRangeReviews($query , $from , $to){
        if($from && !$to){
            return $query->where("created_at" , ">=" ,$from );
        }elseif(!$from && $to){
            return $query->where("created_at" , "<=" ,$to );
        }elseif($from && $to){
            return $query->whereBetween("created_at" ,[$from,$to]);
        }
    }
}
