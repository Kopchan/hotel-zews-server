<?php

namespace App\Http\Requests\News;

use App\Http\Requests\ApiRequest;

class NewsCreateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:128|unique:rooms',
            'text' => 'required|string',
        ];
    }
}
