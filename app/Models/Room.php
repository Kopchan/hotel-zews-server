<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // Заполняемые поля
    protected $fillable = [
        'name',
        'description',
        'price',
        'type_id',
    ];

    // Связи
    public function type() {
        return $this->belongsTo(RoomType::class);
    }
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
    public function photos() {
        return $this->hasMany(Photo::class);
    }
    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
