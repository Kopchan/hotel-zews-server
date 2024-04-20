<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class SignupRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'phone'    => 'required|integer|unique:users',
            'password' => 'required|string|min:8',
            'name'     => 'required|string|min:1|max:32',
            'surname'  => 'required|string|min:1|max:32',
            'patronymic' =>        'string|min:1|max:32',
            'sex'      => 'required|boolean',
            'birthday' => 'required|date|before:+18 years',
            'pass_number'         => 'required|integer',
            'pass_issue_date'     => 'required|date|before:now',
            'pass_birth_address'  => 'required|string|max:64',
            'pass_authority_name' => 'required|string|max:64',
            'pass_authority_code' => 'required|integer|max:999999',
        ];
    }
}
