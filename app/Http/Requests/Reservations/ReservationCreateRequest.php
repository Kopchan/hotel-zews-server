<?php

namespace App\Http\Requests\Reservations;

use App\Http\Requests\ApiRequest;

class ReservationCreateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'room_id' => [
                'required',
                'integer',
                'exists:rooms,id',
            ],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'entry' => [
                'required',
                'date',
                'after:now',
                'before:+'. config('hotel.max_far_book_start') .' days',
            ],
            'exit' => [
                'required_without:nights',
                'date',
                'after:+1 days,entry',
                'before:+'. config('hotel.max_book_period') .' days,entry',
            ],
            'nights' => [
                'required_without:exit',
                'integer',
                'min:1',
                'max:'. config('hotel.max_book_period'),
            ],
        ];
    }
}
