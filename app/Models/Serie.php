<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Serie extends Model
{
    use HasTranslations;
    protected $fillable = ['tmdb_id', 'name', 'overview'];

    public $translatable = ['name', 'overview'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
