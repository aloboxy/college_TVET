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
            'user_id' => 'sometimes|nullable',
            'course_id' => 'sometimes|nullable',
        ];
    }

    public function attributes()
    {
        return  [
            'course_id' => 'Student',
            'user_id' => 'Course',
        ];
    }

    protected function getValidatorInstance()
    {
        $input = $this->all();

        $input['user_id'] = $input['student_id'] ? Qs::decodeHash($input['student_id']) : NULL;

        $this->getInputSource()->replace($input);

        return parent::getValidatorInstance();
    }
}
