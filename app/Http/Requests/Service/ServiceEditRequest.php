<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\ApiRequest;

class ServiceEditRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'string|max:64|unique:services',
            'description' => 'string',
            'photo'       => 'file|mimes:png,jpeg,webp,avif'
        ];
    }
}
