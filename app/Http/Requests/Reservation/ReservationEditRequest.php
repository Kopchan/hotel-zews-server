<?php

namespace App\Http\Requests\Reservation;

use App\Exceptions\ApiException;
use App\Http\Requests\ApiRequest;
use App\Models\Reservation;

class ReservationEditRequest extends ApiRequest
{
    protected function prepareForValidation()
    {
        if (!$this->has('date_entry')) {
            $reservation = Reservation::find($this->route('id'));
            $this->merge(['date_entry' => $reservation?->date_entry]);
        }
    }
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
            'date_entry' => [
                'date',
            ],
            'date_exit' => [
                'date',
                'after:+1 days,entry',
                'before:+'. config('hotel.max_book_period') .' days,date_entry',
            ],
        ];
    }
}
