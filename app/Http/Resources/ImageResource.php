<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class ImageResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'url'=>asset('storage/documents/'.$this->url),
        ];
    }
}
