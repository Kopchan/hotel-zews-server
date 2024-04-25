<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    // Заполняемые поля
    protected $fillable = [
        'name',
    ];

    // Связи
    public function room() {
        return $this->belongsTo(Room::class);
    }
    public function news() {
        return $this->belongsTo(News::class);
    }
    public function services() {
        return $this->hasMany(Service::class);
    }
}
