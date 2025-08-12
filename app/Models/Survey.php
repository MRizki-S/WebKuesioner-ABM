<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = ['name', 'description', 'intro_schema', 'slug', 'total_points'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($survey) {
            $survey->slug = Str::slug($survey->name);
        });
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
