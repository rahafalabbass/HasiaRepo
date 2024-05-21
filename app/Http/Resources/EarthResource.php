<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EarthResource extends JsonResource
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
            'id'=>$this->id,
            'number'=>$this->number,
            'space'=>$this->space,
            'electricity'=>$this->electricity,
            'price'=>$this->price,
            // 'available'=>$this->available,
            'area'=>$this->area->name
            
        ];
    }
}
