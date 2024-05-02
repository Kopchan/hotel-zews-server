<?php

namespace App\Http\Requests\User;

use App\Http\Requests\ApiRequest;

class UserEditRequest extends ApiRequest
{
    public function rules(): array
    {

        return [
            'phone'      => "integer|unique:users,phone,$this->route('id')",
            'password'   => 'string|min:8',
            'name'       => 'string|min:1|max:32',
            'surname'    => 'string|min:1|max:32',
            'patronymic' => 'string|min:1|max:32',
            'sex'        => 'boolean',
            'birthday'   => 'date|before:+18 years',
            'pass_number'         => "integer|unique:users,pass_number,$this->route('id')",
            'pass_issue_date'     => 'date|before:now',
            'pass_birth_address'  => 'string|max:64',
            'pass_authority_name' => 'string|max:64',
            'pass_authority_code' => 'integer|max:999999',
            'role' => 'string|exists:roles,code',
        ];
    }
}
