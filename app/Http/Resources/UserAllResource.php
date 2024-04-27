<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAllResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $response = [
            'id'         => $this->id,
            'phone'      => $this->phone,
            'name'       => $this->name,
            'surname'    => $this->surname,
            'patronymic' => $this->patronymic,
            'birthday'   => $this->birthday,
            'sex'        => $this->sex,
            'pass_number'         => $this->pass_number,
            'pass_issue_date'     => $this->pass_issue_date,
            'pass_birth_address'  => $this->pass_birth_address,
            'pass_authority_name' => $this->pass_authority_name,
            'pass_authority_code' => $this->pass_authority_code,
        ];

        $role = Role::find($this->role_id)->code;
        if ($role !== 'user')
            $response['role'] = $role;

        return $response;
    }
}
