<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Reservations\ReservationCreateSelfRequest;
use App\Models\Reservation;
use App\Models\Room;

class ReservationController extends Controller
{
    public function createSelf(ReservationCreateSelfRequest $request, int $roomId) {
        $room = Room::find($roomId);
        if (!$room)
            throw new ApiException(404, 'Room not found');

        $user = $request->user();
        $err = 'You already have reserved room';
        $query = Reservation
        ::where('user_id', $user->id)
        ->where('date_entry', '>', now());

        if (config('hotel.can_book_with_exist_book')) {
            $err = 'You already reserved this room';
            $query->where('room_id', $room->id);
        }
        if ($query->count())
            throw new ApiException(409, $err);

        $entryDate = $request->entry;
        $exitDate  = $request->exit
            ? $request->exit
            : (new \DateTime("$request->entry +$request->nights days"))
                ->format('Y-m-d');

        $isCollision = Reservation
        ::where('room_id', $room->id)
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

        Reservation::create([
            'date_entry' => $entryDate,
            'date_exit' => $exitDate,
            'room_id' => $room->id,
            'user_id' => $user->id,
            'price' => $room->price,
        ]);
        return response(null, 204);
    }
    public function deleteSelf(int $roomId) {
        $user = request()->user();

        $room = Room::find($roomId);
        if (!$room)
            throw new ApiException(404, 'Room not found');

        $reservation = Reservation
        ::where('room_id', $room->id)
        ->where('user_id', $user->id)
        ->where('date_entry', '>', now())
        ->first();
        if (!$reservation)
            throw new ApiException(404, 'Reservation not found');

        $reservation->delete();
        return response(null, 204);
    }

    public function showAll() {
        $reservations = Reservation::with(['room', 'user'])->get();
        return response(['reservations' => $reservations]);
    }
    public function show(int $id) {
        $reservation = Reservation::with(['room', 'user'])->find($id);

        if (!$reservation)
            throw new ApiException(404, 'Reservation not found');

        return response($reservation);
    }
}
