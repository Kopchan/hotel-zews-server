<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPrivateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'FIO' => $this->getFIO(),
        ];
    }
}
