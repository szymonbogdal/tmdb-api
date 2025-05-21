<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
    use HasTranslations;

    protected $fillable = ['tmdb_id', 'title', 'overview'];

    public $translatable = ['title', 'overview'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
