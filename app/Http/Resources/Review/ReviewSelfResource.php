<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\Room\RoomResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewSelfResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'created' => $this->created_at,
            'grade'   => $this->grade,
            'text'    => $this->text,
            'room'    => RoomResource::make($this->room),
        ];
    }
}
