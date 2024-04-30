<?php

namespace App\Http\Requests\Reservations;

use App\Http\Requests\ApiRequest;

class ReservationEditRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'room_id' => [
                'integer',
                'exists:rooms,id',
            ],
            'user_id' => [
                'integer',
                'exists:users,id',
            ],
            'entry' => [
                'date',
                'after:now',
                'before:+'. config('hotel.max_far_book_start') .' days',
            ],
            'exit' => [
                'date',
                'after:+1 days,entry',
                'before:+'. config('hotel.max_book_period') .' days,entry',
            ],
        ];
    }
}
