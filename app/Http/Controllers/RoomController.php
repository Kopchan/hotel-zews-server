<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Room\RoomCreateRequest;
use App\Http\Requests\Room\RoomEditRequest;
use App\Models\Image;
use App\Models\Photo;
use App\Models\Room;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function showAll() {
        $currentDate = new \DateTime(now());
        $roomsFromDB = Room::with(['reservations', 'photos'])->get();

        $rooms = [];
        foreach ($roomsFromDB as $room) {
            $days = 0;
            foreach ($room['reservations'] as $reservation) {
                $diff = $currentDate->diff(new \DateTime($reservation->date_exit));

                if ($days < $diff->days + 1)
                    $days = $diff->days + 1;
            }
            $rooms[] = [
                'id'            => $room['id'],
                'name'          => $room['name'],
                'description'   => $room['description'],
                'price'         => $room['price'],
                'daysWhenAllow' => $days,
                'photos'        => $room['photos'],
            ];
        }
        return response(['rooms' => $rooms]);
    }
    public function show(int $id) {
        $room = Room::with(['reservations', 'photos'])->find($id);

        if (!$room)
            throw new ApiException(404, 'Room not found');

        $currentDate = new \DateTime(now());
        $days = 0;
        foreach ($room['reservations'] as $reservation) {
            $diff = $currentDate->diff(new \DateTime($reservation->date_exit));

            if ($days < $diff->days + 1)
                $days = $diff->days + 1;
        }
        $room = [
            'id'            => $room['id'],
            'name'          => $room['name'],
            'description'   => $room['description'],
            'price'         => $room['price'],
            'daysWhenAllow' => $days,
            'photos'        => $room['photos'],
        ];
        return response($room);
    }
    public function create(RoomCreateRequest $request) {
        $room = Room::create($request->all());

        $photos = [];
        foreach ($request->file['photos'] as $file) {
            $fileName = $file->getClientOriginalName();
            $fileExt  = $file->extension();

            // Валидация файла
            $validator = Validator::make(['file' => $file], [
                'file' => 'mimes:png,jpeg,webp,avif',
            ]);
            if ($validator->fails()) {
                // Сохранение плохого ответа API
                $response['errors'][] = [
                    'name'    => $fileName,
                    'message' => $validator->errors(),
                ];
                continue;
            }

            $imageHash = md5(File::get($file->getRealPath()));

            // FIXME: Однотипные фото (ванной, например) могут "перетянуть" другой номер
            $image = Photo::firstOrCreate(['name', $imageHash.$fileExt]);
            $image->room_id = $room->id;

            $photos[] = $imageHash.$fileExt;
        }
        $response['room'] = [
            ...$room,
            'photos' => $photos
        ];

        return response($response, 201);
    }
    public function edit(RoomEditRequest $request, int $id) {
        $room = Room::find($id);

        if (!$room)
            throw new ApiException(404, 'Room not found');

        $room->update($request->all());
        return response(null, 204);
    }
    public function delete(int $id) {
        $room = Room::find($id);

        if (!$room)
            throw new ApiException(404, 'User not found');

        $room->delete();
        return response(null, 204);
    }
}
