<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use App\Http\Resources\SerieResource;

class SerieController extends Controller
{
    public function index(){
        return SerieResource::collection(Serie::with('genres')->get());
    }

    public function show($id){
        $serie = Serie::with('genres')->findOrFail($id);
        return new SerieResource($serie);
    }
}
