<?php

namespace App\Http\Requests\Mark;

use Illuminate\Foundation\Http\FormRequest;

class CourseSelector extends FormRequest
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
            'time_tables_id' => 'required|exists:time_tables,id',
            'student_id' => 'required|exists:student_records,id',
        ];
    }

    public function attributes()
    {
        return  [
            'time_tables_id' => 'required|exists:time_tables,id',
            'student_id' => 'required|exists:student_records,id',
        ];
    }
}
