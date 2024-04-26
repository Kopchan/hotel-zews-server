<?php

namespace App\Http\Requests\Reservations;

use App\Http\Requests\ApiRequest;

class ReservationCreateSelfRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'data_enter' => 'required|date|before:now',
            'data_exit'  => 'required|date|before:now',
        ];
    }
}
