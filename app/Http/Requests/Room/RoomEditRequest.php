<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\ApiRequest;

class RoomEditRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'string|max:32|unique:rooms',
            'description' => 'string',
            'price'       => 'decimal|min:0|max:99999999999999.99',
            'type_id'     => 'integer|exists:room_types,id',
        ];
    }
}
