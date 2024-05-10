<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use \App\Http\Requests\Room\RoomTypeRequest;
use App\Models\Room;
use App\Models\RoomType;

class RoomTypeController extends Controller
{
    public function showAll()
    {
        $typesFromDB = RoomType::all();

        foreach ($typesFromDB as $type)
            $types["$type->id"] = $type->name;

        return response(['roomTypes' => $types ?? []]);
    }
    public function show(int $id)
    {
        $type = RoomType::find($id);

        if (!$type)
            throw new ApiException(404, 'Room type not found');

        return response($type->name);
    }
    public function create(RoomTypeRequest $request)
    {
        $type = RoomType::create($request->validated());

        return response(null, 204);
    }
    public function edit(RoomTypeRequest $request, int $id)
    {
        $type = RoomType::find($id);

        if (!$type)
            throw new ApiException(404, 'Room type not found');

        $type->update($request->validated());

        return response(null, 204);
    }
    public function delete(int $id)
    {
        $type = RoomType::find($id);

        if (!$type)
            throw new ApiException(404, 'Room type not found');

        $type->delete();
        return response(null, 204);
    }
}
