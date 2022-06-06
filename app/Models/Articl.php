<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Articl extends Model
{
    protected $fillable = ["title", "body", "category_id", "image"];
    public static function boot()
    {
        parent::boot();
        static::creating(function ($articl) {
            // Before saving, first extract the "id" and "lo setea"
            $articl->user_id = Auth::id(); // Assign to the property "user_id" the value that contain the "id" of the user that are working
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class); // Pending
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Pending
    }
    use HasFactory;
}
