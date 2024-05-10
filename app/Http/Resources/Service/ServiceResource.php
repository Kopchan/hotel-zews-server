<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $response = [
            'id'   => $this->id,
            'name' => $this->name,
        ];
        if ($this->description) $response['description'] = $this->description;
        if ($this->photo)       $response['photo'] = [
            'id'   => $this->photo->id,
            'name' => $this->photo->name,
        ];
        if (count($this->items)) {
            $response['items'] = ServiceItemResource::collection($this->items);
        }
        return $response;
    }
}
