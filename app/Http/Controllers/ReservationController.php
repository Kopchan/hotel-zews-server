<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Reservations\ReservationCreateSelfRequest;
use App\Models\Reservation;
use App\Models\Room;

class ReservationController extends Controller
{
    public function createSelf(ReservationCreateSelfRequest $request, int $roomId) {
        $user = $request->user();

        $room = Room::find($roomId);
        if (!$room)
            throw new ApiException(404, 'Room not found');

        $reservation = Reservation
            ::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->where('date_entry', '>', now())
            ->first();
        if ($reservation)
            throw new ApiException(404, 'You already reserved this room');

        $entryDate = $request->entry;
        $endDate   = $request->exit;
        $existReservation = Reservation
            ::orWhere(function ($q01) use ($entryDate, $endDate) {
             $q01->where('date_entry', '>=', $entryDate)
                 ->where('date_exit' , '<=', $endDate);
            })
            ->orWhere(function ($q02) use ($entryDate, $endDate) {
                $q02->where('date_entry', '<=', $entryDate)
                    ->where('date_exit' , '>=', $entryDate);
            })
            ->count();
        if ($existReservation)
            throw new ApiException(404, 'Room is already occupied for these dates');

        Reservation::create([
            'date_entry' => $request->entry,
            'date_exit' => $request->exit,
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
