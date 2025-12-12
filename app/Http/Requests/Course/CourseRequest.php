<?php

namespace App\Http\Requests\TimeTable;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'student_id' => 'required|exists:student_records,id',
            'time_table_id' => 'required|exists:time_tables,id',
            'year' => 'required|exists:settings,current_session',
        ];
    }

    public function attributes()
    {
        return  [
            'student_id' => 'required|exists:student_records,id',
            'time_table_id' => 'required|exists:time_tables,id',
            'year' => 'required|exists:settings,current_session',
        ];
    }

}
