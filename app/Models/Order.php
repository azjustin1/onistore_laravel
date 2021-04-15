<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";

    protected $fillable = ["user_id", "email", "phone", "address", "price", "note"];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsToMany(Product::class)->withPivot("quantity");
    }
}
