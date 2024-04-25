<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\ApiRequest;

class RoomCreateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:32|unique:rooms',
            'description' => 'required|string',
            'price'       => 'required|decimal:0,2|min:0|max:99999999999999.99',
            'type_id'     => 'required|integer|exists:room_types,id',
            'photos'      => 'array',
        ];
    }
}
