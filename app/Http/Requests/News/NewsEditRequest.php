<?php

namespace App\Http\Requests\News;

use App\Exceptions\ApiException;
use App\Http\Requests\ApiRequest;

class NewsEditRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name'        => "string|max:128|unique:rooms,name,$this->route('id')",
            'description' => 'string',
            'price'       => 'decimal:0,2|min:0|max:99999999999999.99',
            'type_id'     => 'integer|exists:room_types,id',
            'removePhotos' => 'array',
        ];
    }
}
