<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Reservations\ReservationCreateRequest;
use App\Http\Requests\Reservations\ReservationCreateSelfRequest;
use App\Http\Requests\Reservations\ReservationFiltersRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\Room;

class ReservationController extends Controller
{
    public function createSelf(ReservationCreateSelfRequest $request, int $roomId) {
        Reservation::validateAndCreate(
            $roomId,
            $request->user()->id,
            $request->entry,
            $request->exit,
            $request->nights,
        );
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

    public function showAll(ReservationFiltersRequest $request) {
        $query = Reservation::with(['room', 'user']);

        if ($request->users) $query->whereIn('user_id', $request->users);
        if ($request->rooms) $query->whereIn('room_id', $request->rooms);

        return response(['reservations' => ReservationResource::collection($query->get())]);
    }
    public function show(int $id) {
        $reservation = Reservation::with(['room', 'user'])->find($id);

        if (!$reservation)
            throw new ApiException(404, 'Reservation not found');

        return response(ReservationResource::make($reservation));
    }
    public function create(ReservationCreateRequest $request) {
        $reservation = Reservation::validateAndCreate(
            $request->room_id,
            $request->user_id,
            $request->entry,
            $request->exit,
            $request->nights,
        );

        return response(ReservationResource::make($reservation), 201);
    }
    /*
    public function edit(ReservationEditRequest $request, int $id) {
        $reservation = Reservation::find($id);
        if (!$reservation)
            throw new ApiException(404, 'Room not found');

        $reservation->update($request->validated());
        $response = $reservation->loadPhotos($request->file('photos'));

        foreach ($request->removePhotos ?? [] as $removePhoto) {
            $photo = Photo::find($removePhoto);
            if ($photo) {
                $photo->room_id = null;
                $photo->save();
            }
        }

        $response['room'] = RoomResource::make($room);

        return response($response);
    }
    public function delete(int $id) {
        $room = Room::find($id);

        if (!$room)
            throw new ApiException(404, 'Room not found');

        $room->delete();
        return response(null, 204);
    }*/
}
