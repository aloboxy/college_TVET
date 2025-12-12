<?php

namespace App\Http\Requests\Course;

use App\Helpers\Qs;
use Illuminate\Foundation\Http\FormRequest;

class CourseCreate extends FormRequest
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
            'my_class_id' => 'sometimes|nullable',
            'teacher_id' => 'required',
            'term_id' => 'sometimes|nullable',
            'section_id' => 'sometimes|nullable',
            'another_section' => 'sometimes|nullable',
            'subject_id' => 'required',
            'session' => 'required',
            'time_from' => 'required|string',
            'time_to' => 'required|string',
            'day' => 'required',
            'for_all' => 'sometimes|nullable',
            'capacity' => 'sometimes|nullable',
            'room' => 'required|string',
            'year'=>'sometimes|nullable',
            'department_id'=>'sometimes|nullable',
            'level' => 'sometimes|nullable'
        ];
    }

    public function attributes()
    {
        return  [
            'teacher_id' => 'Teacher',
            'subject_id' => 'Subject',
            'section_id' => 'Cohort',
        ];
    }


    // public function setDayAttribute($value)
    // {
    //     $this->attributes['day'] = json_encode($value);
    // }

    // public function getDayAttribute($value)
    // {
    //     return $this->attributes['day'] = json_decode($value);
    // }

    protected function getValidatorInstance()
    {
        $input = $this->all();

        $input['teacher_id'] = $input['teacher_id'] ? Qs::decodeHash($input['teacher_id']) : NULL;

        $this->getInputSource()->replace($input);

        return parent::getValidatorInstance();
    }
}
