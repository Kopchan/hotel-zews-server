<?php

namespace App\Http\Requests\Reservation;

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
            'date_entry' => [
                'required',
                'date',
                'after:now',
                'before:+'. config('hotel.max_far_book_start') .' days',
            ],
            'date_exit' => [
                'required_without:nights',
                'date',
                'after:+1 days,date_entry',
                'before:+'. config('hotel.max_book_period') .' days,date_entry',
            ],
            'nights' => [
                'required_without:date_exit',
                'integer',
                'min:1',
                'max:'. config('hotel.max_book_period'),
            ],
        ];
    }
}
