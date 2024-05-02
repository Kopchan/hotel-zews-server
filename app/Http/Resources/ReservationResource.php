<?php

namespace App\Http\Resources;

use App\Exceptions\ApiException;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $response = [
            'id'      => $this->id,
            'entry'   => $this->date_entry,
            'exit'    => $this->date_exit,
            'created' => $this->created_at,
            'user'    => UserResource::make($this->user),
            'room'    => RoomResource::make($this->room),
        ];
        return $response;
    }
}
