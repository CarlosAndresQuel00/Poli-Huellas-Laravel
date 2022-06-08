<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $fillable = ['text'];
    public static function boot()
    {
        parent::boot();
        // Creating comment
        static::creating(function ($comment) {
            // Before saving, first extract the "id" and "lo setea"
            $comment->user_id = Auth::id(); // Assign to the property "user_id" the value that contain the "id" of the user that are working
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Pending
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class); // Pending
    }

    use HasFactory;
}
