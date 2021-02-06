<?php

namespace App\Http\Requests;

use App\Models\Equipment;
use Illuminate\Foundation\Http\FormRequest;

class AcceptFormRequest extends FormRequest
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
        $emptyEquipment = new Equipment();

        return [
            'equipment_id' => [
                'required',
                'integer',
                'exists:' . $emptyEquipment->getTable() . ',id'
            ],
            'checklist' => [
                'nullable',
                'string',
            ],
        ];
    }
}
