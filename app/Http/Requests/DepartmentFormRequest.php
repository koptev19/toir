<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentFormRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'short_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'manager_id' => [
                'required',
                'exists:users,id',
            ],
        ];
    }
}
