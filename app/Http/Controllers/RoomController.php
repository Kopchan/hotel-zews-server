<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Room\RoomCreateRequest;
use App\Http\Requests\Room\RoomEditRequest;
use App\Http\Resources\RoomResource;
use App\Models\Photo;
use App\Models\Room;

class RoomController extends Controller
{
    public function showAll() {
        $rooms = Room::with(['reservations', 'photos'])->get();

        return response(['rooms' => RoomResource::collection($rooms)]);
    }
    public function show(int $id) {
        $room = Room::with(['reservations', 'photos'])->find($id);
        if (!$room)
            throw new ApiException(404, 'Room not found');

        return response(RoomResource::make($room));
    }
    public function create(RoomCreateRequest $request) {
        $room = Room::create($request->validated());
        $response = $room->loadPhotos($request->file('photos'));

        $response['room'] = RoomResource::make($room);

        return response($response, 201);
    }
    public function edit(RoomEditRequest $request, int $id) {
        $room = Room::find($id);
        if (!$room)
            throw new ApiException(404, 'Room not found');

        $room->update($request->validated());
        $response = $room->loadPhotos($request->file('photos'));

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
    }
}
