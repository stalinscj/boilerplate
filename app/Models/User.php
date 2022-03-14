<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's level.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function level(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getLowestRoleLevel(),
        );
    }

    /**
     * Returns the user's minimum role level.
     *
     * @return int
     */
    public function getLowestRoleLevel()
    {
        $roles = $this->roles;

        if ($roles->isEmpty()) {
            return 32767;
        }

        return $roles->min('level');
    }
}
