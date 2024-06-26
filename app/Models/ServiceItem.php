<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'service_id',
    ];

    // Связи
    public function service() {
        return $this->belongsTo(Service::class);
    }
}
