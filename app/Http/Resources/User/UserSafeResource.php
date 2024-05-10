<?php

namespace App\Http\Resources\User;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSafeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $response = [
            'FIO'        => $this->getFIO(),
            'phone'      => $this->phone,
            'name'       => $this->name,
            'surname'    => $this->surname,
            'patronymic' => $this->patronymic,
            'birthday'   => $this->birthday,
            'sex'        => $this->sex,
        ];

        $role = Role::find($this->role_id)->code;
        if ($role !== 'user')
            $response['role'] = $role;

        return $response;
    }
}
