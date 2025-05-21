<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Genre extends Model
{
    use HasTranslations;

    protected $fillable = ['tmdb_id', 'name', 'overview', 'first_air_date', 'poster_path'];

    public $translatable = ['name', 'overview'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
