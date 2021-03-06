<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['type'];
    public function users()
    {
        return $this->belongsToMany(User::class)->as('subscriptions')->withTimestamps();
    }
    use HasFactory;
}
