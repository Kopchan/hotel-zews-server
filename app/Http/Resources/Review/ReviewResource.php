<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\Room\RoomResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'created'   => $this->created_at,
            'grade'     => $this->grade,
            'text'      => $this->text,
            'moderated' => $this->is_moderated,
            'user'      => UserResource::make($this->user),
            'room'      => RoomResource::make($this->room),
        ];
    }
}
