<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'created' => $this->created_at,
            'grade'   => $this->grade,
            'text'    => $this->text,
            'user'    => UserResource::make($this->user),
            'room'    => RoomResource::make($this->room),
        ];
    }
}
