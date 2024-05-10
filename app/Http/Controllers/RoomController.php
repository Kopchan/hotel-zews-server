<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Room\RoomCreateRequest;
use App\Http\Requests\Room\RoomEditRequest;
use App\Http\Requests\Room\RoomFilterRequest;
use App\Http\Resources\Room\RoomResource;
use App\Models\Photo;
use App\Models\Room;

class RoomController extends Controller
{
    public function showAll(RoomFilterRequest $request)
    {
        $query = Room::query()
            ->leftJoin('reservations', 'reservations.room_id',  'rooms.id')
//            ->join('photos',       'photos.room_id',        'rooms.id')
//            ->join('reviews',      'reviews.room_id',       'rooms.id')
            ->selectRaw('rooms.*')
//            ->groupBy('rooms.name')
            ;
//        if ($request->limit     ) $query->limit($request->limit);
//        if ($request->date_entry) $query->where();
//        if ($request->date_exit ) $query->where();

        if ($request->date)

        $sort = (in_array($request->sort, ['price', 'avg_grade', 'type'])
            ? $request->sort
            : 'avg_grade'
        );
        $reverse = ($request->has('reverse')
            ? 'desc'
            : 'asc'
        );
       // $query->orderBy($sort, $reverse);

//        return $query->toSql();
        return $query->get();
        return response(['rooms' => RoomResource::collection($query->get())]);
    }
    public function show(int $id)
    {
        $room = Room::with(['reservations', 'photos', 'reviews'])->find($id);
        if (!$room)
            throw new ApiException(404, 'Room not found');

        return response(RoomResource::make($room));
    }
    public function create(RoomCreateRequest $request)
    {
        $room = Room::create($request->validated());
        $response = $room->loadPhotos($request->file('photos'));

        $response['room'] = RoomResource::make($room);

        return response($response, 201);
    }
    public function edit(RoomEditRequest $request, int $id)
    {
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
    public function delete(int $id)
    {
        $room = Room::find($id);

        if (!$room)
            throw new ApiException(404, 'Room not found');

        $room->delete();
        return response(null, 204);
    }
}
