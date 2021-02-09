<?php

namespace App\Http\Requests;

use App\Models\Accept;
use App\Traits\UploadFileTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AcceptHistoryFormRequest extends FormRequest
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
        $emptyAccept = new Accept();

        return [
            'accept_id' => [
                'required',
                'integer',
                'exists:' . $emptyAccept->getTable() . ',id'
            ],
            'fio' => [
                'required',
            ],
            'comment' => [
                'nullable',
            ],
            'files' => [
                'nullable',
                'array',
            ],
            'files.*' => [
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
            $this->uploadFileMultiple('files', 'files_added');
        });

        return $validator;
    }
}
