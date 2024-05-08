<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $response = [
            'id'      => $this->id,
            'name'    => $this->name,
            'created' => $this->created_at,
            'text'    => $this->text,
        ];
        if (count($this->photos)) {
            foreach ($this->photos as $photo) {
                $photos["img_$photo->id"] = $photo->name;
            }
            $response['photos'] = $photos ?? [];
        }
        return $response;
    }
}
