<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
        $emptyUser = new User;

        return [
            'connected' => [
                'nullable',
                'array'
            ],
            'connected.*' => [
                'integer',
                'exists:' . $emptyUser->getTable() . ',id'
            ],
            'is_admin' => [
                'nullable',
                'array'
            ],
            'is_admin.*' => [
                'integer',
                'exists:' . $emptyUser->getTable() . ',id'
            ],
            'departments' => [
                'nullable',
                'array'
            ],
            'all_workshops' => [
                'nullable',
                'array'
            ],
            'all_workshops.*' => [
                'integer',
                'exists:' . $emptyUser->getTable() . ',id'
            ],
            'workshops' => [
                'nullable',
                'array'
            ],
        ];
    }
}
