<?php

namespace App\Http\Requests\Course;

use App\Helpers\Qs;
use Illuminate\Foundation\Http\FormRequest;

class CourseUpdate extends FormRequest
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
            'teacher_id' => 'required',
            'term_id' => 'sometimes|nullable',
            'section_id' => 'sometimes|nullable',
            'another_section' => 'sometimes|nullable',
            'session' => 'required',
            'time_from' => 'required|string',
            'time_to' => 'required|string',
            'day' => 'required',
            'for_all' => 'sometimes|nullable',
            'capacity' => 'sometimes|nullable',
            'room' => 'required|string',
            'department_id'=>'sometimes|nullable',
            'level'=>'sometimes|nullable',

        ];
    }

    public function attributes()
    {
        return  [

            'teacher_id' => 'Teacher',
            'slug' => 'Short Name',
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
