<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Pet extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'type',
        'size',
        'description',
        'date_of_birth',
        'category_id',
        'image'
    ];
    public static function boot()
    {
        parent::boot();
        static::creating(function ($pet) {
            // Before saving, first extract the "id" and "lo setea"
            $pet->user_id = Auth::id(); // Assign to the property "user_id" the value that contain the "id" of the user that are working
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
