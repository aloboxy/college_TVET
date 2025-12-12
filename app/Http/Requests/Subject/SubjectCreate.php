<?php

namespace App\Http\Requests\Subject;

use App\Helpers\Qs;
use Illuminate\Foundation\Http\FormRequest;

class SubjectCreate extends FormRequest
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
            'department_id' => 'required',
            'teacher_id' => 'sometimes|nullable',
            'clinical' => 'sometimes|nullable',
            'term_id' => 'sometimes|nullable',
            'credit' => 'sometimes|nullable',
            'slug' => 'sometimes|nullable',
            'level' => 'nullable|string|min:3',
            'prerequisite_id' => 'nullable|numeric|exist:subjects,id',

        ];
    }

    public function attributes()
    {
        return  [
            'department_id' => 'Department',
            'slug' => 'Short Name',
            'term_id' => 'term',
        ];
    }

    // protected function getValidatorInstance()
    // {
    //     // $input = $this->all();

    //     // $input['teacher_id'] = $input['teacher_id'] ? Qs::decodeHash($input['teacher_id']) : NULL;

    //     // $this->getInputSource()->replace($input);

    //     // return parent::getValidatorInstance();
    // }
}
