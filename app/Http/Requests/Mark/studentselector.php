<?php

namespace App\Http\Requests\Mark;

use Illuminate\Foundation\Http\FormRequest;

class studentselector extends FormRequest
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
            'exam_id' => 'required|exists:exams,id',
            'my_class_id' => 'required|exists:my_classes,id',
            'section_id' => 'required|exists:sections,id',
            'course_id' => 'required|exists:courses,id',
            'term_id' => 'sometimes|nullable',
        ];
    }

    public function attributes()
    {
        return  [
            'exam_id' => 'Exam',
            'my_class_id' => 'Class',
            'section_id' => 'Section',
            'subject_id' => 'Subject',
        ];
    }
}
