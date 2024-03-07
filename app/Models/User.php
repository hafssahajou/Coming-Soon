<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;

class User extends AuthenticatableUser implements Authenticatable
{
    use HasFactory;

    // protected $guarded = [];
    protected $fillable = [
        'name',
        'email',
        'password',
        'picture',
        
    ];

    public function Organisateur()
    {
        return $this->hasMany(Organisateur::class);
    }
    public function Client()
    {
        return $this->hasMany(Client::class);
    }
    
}