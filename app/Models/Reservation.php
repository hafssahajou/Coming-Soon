<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function ticket()
    {
        return $this->hasone(Ticket::class);
    }
}