<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{

 use HasApiTokens;


    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function refreshTokens()
    {
        return $this->hasMany(RefreshToken::class, 'admin_id');
    }
}
