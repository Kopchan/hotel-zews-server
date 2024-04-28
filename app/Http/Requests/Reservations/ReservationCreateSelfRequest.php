<?php

namespace App\Http\Requests\Reservations;

use App\Http\Requests\ApiRequest;

class ReservationCreateSelfRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
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
