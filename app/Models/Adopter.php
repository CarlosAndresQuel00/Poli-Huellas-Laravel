<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adopter extends Model
{
    use HasFactory;

    protected $fillable = [ 'company', 'short_bio' ];
    public $timestamps = false;

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
