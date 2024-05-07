<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Reservation\ReservationCreateRequest;
use App\Http\Requests\Reservation\ReservationCreateSelfRequest;
use App\Http\Requests\Reservation\ReservationEditRequest;
use App\Http\Requests\Reservation\ReservationFiltersRequest;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\ReservationSelfResource;
use App\Models\Reservation;
use App\Models\Room;

class ReservationController extends Controller
{
    public function createSelf(ReservationCreateSelfRequest $request, int $roomId)
    {
        Reservation::validateAndCreate(
            $roomId,
            null,
            $request->date_entry,
            $request->date_exit,
            $request->nights,
        );
        return response(null, 204);
    }
    public function deleteSelf(int $roomId)
    {
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

    public function showAllSelf(ReservationFiltersRequest $request)
    {
        $query = Reservation::with(['room']);

        if ($request->users) $query->where  ('user_id', $request->user()->id);
        if ($request->rooms) $query->whereIn('room_id', $request->rooms);

        return response(['reservations' => ReservationSelfResource::collection($query->get())]);
    }

    public function showAll(ReservationFiltersRequest $request)
    {
        $query = Reservation::with(['room', 'user']);

        if ($request->users) $query->whereIn('user_id', $request->users);
        if ($request->rooms) $query->whereIn('room_id', $request->rooms);

        return response(['reservations' => ReservationResource::collection($query->get())]);
    }
    public function show(int $id)
    {
        $reservation = Reservation::with(['room', 'user'])->find($id);

        if (!$reservation)
            throw new ApiException(404, 'Reservation not found');

        return response(ReservationResource::make($reservation));
    }
    public function create(ReservationCreateRequest $request)
    {
        $reservation = Reservation::validateAndCreate(
            $request->room_id,
            $request->user_id,
            $request->date_entry,
            $request->date_exit,
            $request->nights,
        );

        return response(ReservationResource::make($reservation), 201);
    }
    public function edit(ReservationEditRequest $request, int $id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation)
            throw new ApiException(404, 'Reservation not found');

        $reservation->update($request->validated());
        return response(null, 204);
    }
    public function delete(int $id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation)
            throw new ApiException(404, 'Reservation not found');

        $reservation->delete();
        return response(null, 204);
    }
}
