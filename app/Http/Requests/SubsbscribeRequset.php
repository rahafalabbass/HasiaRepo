<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubsbscribeRequset extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
{
    return [
        'name' => ['string', 'required', 'max:100'],
        'father_name' => ['string', 'required'],
        'mother_name' => ['string', 'required'],
        'birth' => ['date', 'date_format:Y-m-d'],
        'ID_personal' => ['required'],
        'address' => ['required'],
        'phone1' => ['required', 'max:10','min:10'],
        'email' => ['email', 'required'],
        'factory_ent' => ['string', 'required', 'max:100'],
        'factory_name' => ['string', 'max:255'],
        'Industry_name' => ['string', 'required'],
        'ID_classification' => ['required'],
        'Money' => ['required'],
        'Num_Worker' => ['required'],
        'Value_equipment' => ['required'],
        'Num_Year_Worker' => ['required'],
        'Num_Exce' => ['required'],
        'Q_Water' => ['required'],
        'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        'earth_id'=>['required','exists:earths,id'],
        'area_id'=>['required','exists:areas,id'],
    ];
}

}
