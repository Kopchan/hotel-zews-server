<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Room\RoomCreateRequest;
use App\Http\Requests\Room\RoomEditRequest;
use App\Http\Requests\Room\RoomFilterRequest;
use App\Http\Resources\Room\RoomAllResource;
use App\Http\Resources\Room\RoomResource;
use App\Models\Photo;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function showAll(RoomFilterRequest $request)
    {
        $query = Room::query();
        $query->selectRaw('rooms.*, avg(reviews.grade) as avg_grade, count(reviews.id) as reviews_count');
        $query->leftJoin('reviews', 'rooms.id', 'reviews.room_id');
        $query->groupBy('rooms.id');

        $query->whereIn('type_id', $request->types);

        if ($request->limit) $query->limit($request->limit);
        if ($request->date_entry) {
            $entryDate = $request->date_entry;
            $exitDate  = $request->date_exit;
            $RoomsWithCollision = Reservation::query()
                ->where(function ($q) use ($entryDate, $exitDate) {
                    $q->orWhere(function ($q01) use ($entryDate, $exitDate) {
                        $q01->where('date_entry', '>=', $entryDate)
                            ->where('date_exit' , '<=', $exitDate);
                    })->orWhere(function ($q02) use ($entryDate, $exitDate) {
                        $q02->where('date_entry', '<=', $exitDate)
                            ->where('date_exit' , '>=', $entryDate);
                    });
                })
                ->pluck('room_id')
                ->toArray();
            $query->whereNotIn('id', $RoomsWithCollision);
        }
        $sort = (in_array($request->sort, ['price', 'avg_grade', 'reviews_count', 'type'])
            ? $request->sort
            : 'avg_grade'
        );
        $reverse = ($request->has('reverse')
            ? 'desc'
            : 'asc'
        );
        $query->orderBy($sort, $reverse);
        return response(['rooms' => RoomResource::collection($query->get())]);
    }
    public function show(int $id)
    {
        $room = Room
            ::query()
            ->selectRaw('rooms.*, avg(reviews.grade) as avg_grade, count(reviews.id) as reviews_count')
            ->leftJoin('reviews', 'rooms.id', 'reviews.room_id')
            ->groupBy('rooms.id')
            ->find($id);
        if (!$room)
            throw new ApiException(404, 'Room not found');

        return response(RoomAllResource::make($room));
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
