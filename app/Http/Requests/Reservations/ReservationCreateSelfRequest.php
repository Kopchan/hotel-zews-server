<?php

namespace App\Http\Requests\Reservations;

use App\Http\Requests\ApiRequest;

class ReservationCreateSelfRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'entry' => 'required|date|after:now',
            'exit'  => 'required|date|after:entry',
        ];
    }
}
