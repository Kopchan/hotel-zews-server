<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class LoginRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'phone'    => 'required|integer',
            'password' => 'required|string|min:8',
        ];
    }
}
