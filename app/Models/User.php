<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'cellphone',
        'address',
        'image',
        'date_of_birth',
        'email',
        'password',
        'role',
        'external_id',
        'external_auth'
    ];
    // With auth
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_PROTECTOR = 'ROLE_PROTECTOR';
    const ROLE_ADOPTER = 'ROLE_ADOPTER';

    private const ROLES_HIERARCHY = [
        self::ROLE_SUPERADMIN => [self::ROLE_ADMIN],
        self::ROLE_ADMIN => [self::ROLE_PROTECTOR, self::ROLE_ADOPTER],
        self::ROLE_PROTECTOR => [],
        self::ROLE_ADOPTER => []
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function pets()
    {
        return $this->hasMany(Pet::class); // One user has many pets
    }

    public function comments()
    {
        return $this->hasMany(Comment::class); // Pending
    }

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->as('subscriptions')->withTimestamps(); // Belong to several categories
    }

    public function isGranted($role)
    {
        if ($role === $this->role) {
            return true;
        }
        return self::isRoleInHierarchy($role, self::ROLES_HIERARCHY[$this->role]);
    }

    private static function isRoleInHierarchy($role, $role_hierarchy)
    {
        if (in_array($role, $role_hierarchy)) {
            return true;
        }
        foreach ($role_hierarchy as $role_included) {
            if(self::isRoleInHierarchy($role,self::ROLES_HIERARCHY[$role_included]))
            {
                return true;
            }
        }
        return false;
    }

    public function userable()
    {
        return $this->morphTo();
    }
}
