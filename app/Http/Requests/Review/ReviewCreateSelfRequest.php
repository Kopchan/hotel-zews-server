<?php

namespace App\Http\Requests\Review;

use App\Http\Requests\ApiRequest;

class ReviewCreateSelfRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'grade' => 'required|integer,min:1,max:5',
            'text'  => 'required|string',
        ];
    }
}
