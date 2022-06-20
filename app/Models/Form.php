<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Form extends Model
{
    protected $fillable = [
        'responsible',
        'reason',
        'home',
        'description',
        'diseases',
        'children',
        'time',
        'trip',
        'new',
        'animals',
        'category_id',
    ];
    public static function boot()
    {
        parent::boot();
        static::creating(function ($form) {
            // Before saving, first extract the "id" and "lo setea"
            $form->user_id = Auth::id(); // Assign to the property "user_id" the value that contain the "id" of the user that are working
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
