<?php

namespace App\Models;

use App\Exceptions\ApiException;
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

    public static function validateAndCreate($roomId, $userId = null, $entryDate, $exitDate = null, $nights = null) {
        $room = Room::find($roomId);
        if (!$room)
            throw new ApiException(404, 'Room not found');

        $err = ($userId ? 'User' : 'You') .' already have reserved room';
        $query = Reservation
            ::where('user_id', $userId ?? request()->user()->id)
            ->where('date_entry', '>', now());

        if (config('hotel.can_book_with_exist_book')) {
            $err = ($userId ? 'User' : 'You') .' already reserved this room';
            $query->where('room_id', $roomId);
        }
        if ($query->count())
            throw new ApiException(409, $err);

        if ($nights) $exitDate =
            (new \DateTime("$entryDate +$nights days"))
            ->format('Y-m-d');

        $isCollision = Reservation
            ::where('room_id', $roomId)
            ->where(function ($q) use ($entryDate, $exitDate) {
                $q->orWhere(function ($q01) use ($entryDate, $exitDate) {
                    $q01->where('date_entry', '>=', $entryDate)
                        ->where('date_exit' , '<=', $exitDate);
                })->orWhere(function ($q02) use ($entryDate, $exitDate) {
                    $q02->where('date_entry', '<=', $exitDate)
                        ->where('date_exit' , '>=', $entryDate);
                });
            })
            ->count();
        if ($isCollision)
            throw new ApiException(400, 'Room is already occupied for these dates');

        return Reservation::create([
            'date_entry' => $entryDate,
            'date_exit'  =>  $exitDate,
            'room_id' => $roomId,
            'user_id' => $userId,
            'price' => $room->price,
        ]);
    }

    // Связи
    public function room() {
        return $this->belongsTo(Room::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
