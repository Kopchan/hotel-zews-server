<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Связи
    public function items() {
        return $this->hasMany(ServiceItem::class);
    }

    // Связи
    public function photo() {
        return $this->belongsTo(Photo::class);
    }
}
