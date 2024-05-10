<?php

namespace App\Http\Resources\Room;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $response = [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'type'        => $this->type->name,
        ];
        if ($this->avg_grade)     $response['avg_grade']     = $this->avg_grade;
        if ($this->reviews_count) $response['reviews_count'] = $this->reviews_count;
        if (count($this->reservations)) {
            $currentDate = new \DateTime(now());
            $days = 0;
            foreach ($this['reservations'] as $reservation) {
                $diff = $currentDate->diff(new \DateTime($reservation->date_exit));

                if ($days < $diff->days + 1)
                    $days = $diff->days + 1;
            }
            $response['daysWhenAllow'] = $days;
        }
        if (count($this->photos)) {
            foreach ($this->photos as $photo) {
                $photos["img_$photo->id"] = $photo->name;
            }
            $response['photos'] = $photos ?? [];
        }
        return $response;
    }
}
