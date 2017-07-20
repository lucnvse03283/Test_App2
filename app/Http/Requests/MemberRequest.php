<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
            'name' => 'required|max:100',
            'address' => 'required|max:300',
            'age' => 'required|digits_between:1,2|regex:/^\d{0,9}(\.\d{1,9})?$/',
            'image' => 'nullable|image|mimes:png,jpeg,gif|max:10240',
        ];
        return $rules;
    }
}
