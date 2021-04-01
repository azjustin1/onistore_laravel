<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'amount', 'slug', "image"];

    public function category() {
        return $this->belongsToMany(Category::class);
    }

    public function image()
    {
        return $this->hasMany(Image::class);
    }

    public function comment() {
        return $this->hasMany(Comment::class);
    }
}
