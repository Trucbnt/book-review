<?php

use Illuminate\Support\Facades\Route;
use App\Models\Book;
Route::get('/', function () {
    $books = Book::with([
        "reviews" => function($query){
        $query->take(1);
    }
    ])->take(2)->get();
    dd($books);
});
 