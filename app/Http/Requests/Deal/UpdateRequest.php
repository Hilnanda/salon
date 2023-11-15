<?php

namespace App\Http\Requests\Deal;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends CoreRequest
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
        $rules = [
            'title'      => 'required',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
            'original_amount' => 'required',
            'discount_amount' => 'required',
            'open_time' => 'required',
            'close_time' => 'required',
        ];
        return $rules;
    }
}
