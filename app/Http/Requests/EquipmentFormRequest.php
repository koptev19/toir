<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentFormRequest extends FormRequest
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
        $rules = [
            'name' => [
                'required',
                'max:255',
            ],
        ];

        if ($this->method() === "POST") {
            $rules = array_merge($rules, $this->rulesStore());
        }

        return $rules;
    }

    /**
     * @return array
     */
    protected function rulesStore()
    {
        return [
            'parent_id' => [
                'nullable',
                'numeric',
            ],
            'type' => [
                'nullable',
            ],
        ];
    }
}
