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
            throw new ApiException(404, 'Reservation not found');

        Reservation::create([
            ...$request->all(),
            'user_id' => $request->user()->id,
            'price' => $room->price,
        ]);
    }
    public function deleteSelf(int $roomId) {
        $room = Room::find($roomId);

        if (!$room)
            throw new ApiException(404, 'Reservation not found');
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
