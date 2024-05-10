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
            'date_entry' => [
                'date',
                'required_with:date_exit',
                'after:now -1 days',
                'before:+'. config('hotel.max_far_book_start') .' days',
            ],
            'date_exit'  => [
                'date',
                'required_with:date_entry',
                'after:+1 days,date_entry',
                'before:+'. config('hotel.max_book_period') .' days,date_entry',
            ],
            'reverse' => 'nullable'
        ];
    }
}
