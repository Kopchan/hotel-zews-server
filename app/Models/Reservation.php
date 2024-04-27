<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // Заполняемые поля
    protected $fillable = [
        'date_entry',
        'date_exit',
        'room_id',
        'user_id',
        'price',
    ];

    // Связи
    public function room() {
        return $this->belongsTo(Room::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
