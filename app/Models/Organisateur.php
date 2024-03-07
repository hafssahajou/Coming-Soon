<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisateur extends User
{
    use HasFactory;
    
    protected $fillable = [
        'phone_number',
        'address',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function event()
    {
        return $this->hasMany(Event::class, 'event_id');
    }
}