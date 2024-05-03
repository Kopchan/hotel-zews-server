<?php

namespace App\Http\Requests\Reservation;

use App\Exceptions\ApiException;
use App\Http\Requests\ApiRequest;

class ReservationFiltersRequest extends ApiRequest
{
    protected function prepareForValidation()
    {
        $this->replace([
            'rooms' => $this->rooms ? explode(',', $this->rooms) : [],
            'users' => $this->users ? explode(',', $this->users) : []
        ]);
    }
    public function rules(): array
    {
        return [
            'rooms' => 'array',
            'users' => 'array',
            'rooms.*' => 'integer',
            'users.*' => 'integer',
        ];
    }
}
