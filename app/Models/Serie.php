<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Serie extends Model
{
    use HasTranslations;
    protected $fillable = ['tmdb_id', 'name'];

    public $translatable = ['name'];

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }

    public function series()
    {
        return $this->belongsToMany(Serie::class);
    }
}
