<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = ["id",'name', 'description', 'fake_price', 'price', 'slug', 'quantity', 'category', "image"];

    public function getRouteKeyName()
    {
        return 'slug';
    }

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

    public function rating() {
        return $this->hasMany(Rating::class);
    }
}
