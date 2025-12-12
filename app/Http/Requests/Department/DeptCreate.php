<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Qs;

class DeptCreate extends FormRequest
{

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
            'name' => 'required|string|min:3',
            'code' => 'required|string|min:2',
            'program' => 'required|string|min:2',
            'college_id' => 'sometimes|nullable',
            'total_credit' => 'sometimes|nullable|numeric',
            'teacher_id'=>'sometimes|nullable',
             'class_base'=>'sometimes|nullable'
        ];
    }

    public function attributes()
    {
        return  [
            'code' => 'code',
        ];
    }

    protected function getValidatorInstance()
    {
        $input = $this->all();

        $input['teacher_id'] = $input['teacher_id'] ? Qs::decodeHash($input['teacher_id']) : NULL;

        $this->getInputSource()->replace($input);

        return parent::getValidatorInstance();
    }

}
