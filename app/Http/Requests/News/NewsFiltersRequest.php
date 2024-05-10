<?php

namespace App\Http\Requests\News;

use App\Http\Requests\ApiRequest;

class NewsFiltersRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'cut'   => 'integer|min:1',
            'limit' => 'integer|min:1'
        ];
    }
}
