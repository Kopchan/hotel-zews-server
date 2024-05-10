<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\ApiRequest;

class RoomFilterRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'limit' => 'integer',
            'types' => 'array',
            'sort'  => 'string', // price|type|grade
            'date_entry' => 'date',
            'date_exit'  => 'date',
            'reverse' => 'nullable'
        ];
    }
}
