<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Room\RoomCreateRequest;
use App\Http\Requests\Room\RoomEditRequest;
use App\Http\Resources\RoomResource;
use App\Models\Photo;
use App\Models\Room;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $room = Room::create($request->all());
        $files = $request->file('photos') ?? [];

        foreach ($files as $file) {
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
            $fileHash = md5(File::get($file->getRealPath()));

            // FIXME: Однотипные фото (ванной, например) могут быть "перетянуты" другим номером
            $imageName = "$fileHash.$fileExt";
            $image = Photo::firstOrCreate(['name' => $imageName]);
            $image->room_id = $room->id;
            $image->save();

            // Сохранение файла в хранилище
            if (!Storage::exists('public'.$imageName))
                $file->storeAs  ('public',$imageName);
        }
        $response['room'] = RoomResource::make($room);

        return response($response, 201);
    }
    public function edit(RoomEditRequest $request, int $id) {
        $room = Room::find($id);
        $files = $request->file('photos') ?? [];

        if (!$room)
            throw new ApiException(404, 'Room not found');

        $room->update($request->all());

        foreach ($files as $file) {
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
            $fileHash = md5(File::get($file->getRealPath()));

            $imageName = "$fileHash.$fileExt";
            $image = Photo::firstOrCreate(['name' => $imageName]);
            $image->room_id = $room->id;
            $image->save();

            // Сохранение файла в хранилище
            if (!Storage::exists('public'.$imageName))
                $file->storeAs  ('public',$imageName);
        }
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