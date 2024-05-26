<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubsecrebResource extends JsonResource
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
            'name'=>$this->name,
            'father_name'=>$this->father_name,
            'mother_name'=>$this->mother_name,
            'birth'=>$this->birth,
            'ID_personal'=>$this->ID_personal,
            'address'=>$this->address,
            'phone1'=>$this->phone1,
            'phone2'=>$this->phone2,
            'email'=>$this->email,
            'factory_name'=>$this->factory_name,
            'factory_ent'=>$this->factory_ent,
            'Industry_name'=>$this->Industry_name,
            'ID_classification'=>$this->ID_classification,
            'Money'=>$this->Money,
            'Num_Worker'=>$this->Num_Worker,
            'Value_equipment'=>$this->Value_equipment,
            'Num_Year_Worker'=>$this->Num_Year_Worker,
            'Num_Exce'=>$this->Num_Exce,
            'Q_Water'=>$this->Q_Water,
            'Q_electricity'=>$this->Q_electricity,
            'Date_Form'=>$this->naDate_Formme,
            'payment_method'=>$this->payment_method,
            'images'=> ImageResource::Collection($this->images),

        ];
    }
}
