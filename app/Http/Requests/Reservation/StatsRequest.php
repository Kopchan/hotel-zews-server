<?php

namespace App\Http\Requests\Reservation;

use App\Http\Requests\ApiRequest;

class StatsRequest extends ApiRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'types' => $this->types ? explode(',', $this->types) : [],
        ]);
    }
    public function rules(): array
    {
        return [
            'types'   => 'array',
            'types.*' => 'integer',
            'start'   => 'required_with:date_end|date',
            'end'     => 'required_with:date_start|date|after:start',
        ];
    }
}
