<?php

namespace App\Http\Requests\Review;

use App\Exceptions\ApiException;
use App\Http\Requests\ApiRequest;
use App\Models\Reservation;

class ReviewEditRequest extends ApiRequest
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
            'text' => [
                'string',
            ],
            'grade' => [
                'string',
            ],
            'is_moderated' => [
                'boolean'
            ]
        ];
    }
}
