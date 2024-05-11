<?php

namespace App\Http\Resources\Reservation;

use App\Http\Resources\Room\RoomResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationSelfResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $response = [
//          'id'      => $this->id,
            'entry'   => $this->date_entry,
            'exit'    => $this->date_exit,
            'created' => $this->created_at,
            'room'    => RoomResource::make($this->room),
            'price'   => $this->price,
        ];
        return $response;
    }
}
