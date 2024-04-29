<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    // Заполняемые поля
    protected $fillable = [
        'name',
        'text',
    ];

    public function LoadPhotos($files) {
        return Photo::LoadArray($files, null, $this->id);
    }

    // Связи
    public function photos() {
        return $this->hasMany(Photo::class);
    }
}
