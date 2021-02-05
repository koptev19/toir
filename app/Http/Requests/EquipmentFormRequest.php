<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
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
                'max:191',
            ],
        ];

        if ($this->method() === "POST") {
            $rules = array_merge($rules, $this->rulesStore());
        }

        if ($this->method() === "PUT") {
            $rules = array_merge($rules, $this->rulesUpdate());
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

    /**
     * @return array
     */
    protected function rulesUpdate()
    {
        $emptyUser = new User;

        return [
            'short_name' => [
                'nullable',
            ],
            'manager_id' => [
                'nullable',
                'integer',
                'exists:' . $emptyUser->getTable() . ',id'
            ],
            'mechanic_id' => [
                'nullable',
                'integer',
                'exists:' . $emptyUser->getTable() . ',id'
            ],
            'inventory_number' => [
                'nullable',
            ],
            'enter_date' => [
                'nullable',
                'date',
            ],
            'description' => [
                'nullable',
            ],
            'photo' => [
                'nullable',
                'file',
            ],
            'sketch' => [
                'nullable',
                'file',
          ],
            'documents' => [
                'nullable',
                'array',
            ],
            'documents.*' => [
                'file',
            ],
        ];
    }
}
