<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\ApiRequest;

class RoomTypeRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:32|unique:room_types',
        ];
    }
}
