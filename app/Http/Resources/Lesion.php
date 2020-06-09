<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Lesion extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'analyse' => $this->analyse,
            'original_image' => asset('storage' . $this->original_image),
            'checked_image' => asset('storage' . $this->checked_image),
            'classified_image' => $this->classified_image,
            'accuracy' => $this->accuracy,
            'sensitivity' => $this->sensitivity,
            'specificity' => $this->specificity,
            'dice' => $this->dice,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
