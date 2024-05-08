<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\ApiRequest;

class ReviewCreateRequest extends ApiRequest
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
            'text' => [
                'required',
                'string',
            ],
            'grade' => [
                'required',
                'string',
            ],
            'is_moderated' => [
                'boolean'
            ]
        ];
    }
}
