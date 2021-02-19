<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Traits\UploadFileTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EquipmentFormRequest extends FormRequest
{
    use UploadFileTrait;

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
            'is_repair' => [
                'nullable',
                'boolean',
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

    /**
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator)  {
            $this->uploadFile('photo');
            $this->uploadFile('sketch');
            $this->uploadFileMultiple('documents', 'documents_added');
        });

        return $validator;
    }

    /**
     * @return array
     */
    public function validated()
    {
        $validated = parent::validated();

        if($this->photo_del) {
            $validated['photo_id'] = null;
        }

        if($this->photo) {
            $validated['photo_id'] = $this->photo_id;
        }

        if($this->sketch_del) {
            $validated['sketch_id'] = null;
        }

        if($this->sketch) {
            $validated['sketch_id'] = $this->sketch_id;
        }

        return $validated;
    }


}
