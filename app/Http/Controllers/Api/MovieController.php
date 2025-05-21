<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Http\Resources\MovieResource;

class MovieController extends Controller
{
    public function index(){
        return MovieResource::collection(Movie::with('genres')->get());
    }

    public function show($id){
        $movie = Movie::with('genres')->findOrFail($id);
        return new MovieResource($movie);
    }
}
