<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\ApiRequest;

class ServiceCreateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:64|unique:services',
            'description' => 'required|string',
            'photo'       => 'file|mimes:png,jpeg,webp,avif'
        ];
    }
}
