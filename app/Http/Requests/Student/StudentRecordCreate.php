<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Qs;

class StudentRecordCreate extends FormRequest
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
            'name' => 'required|string|min:6|max:150',
            'adm_no' => 'sometimes|nullable|alpha_num|min:3|max:150|unique:student_records',
            'gender' => 'required|string',
            'year_admitted' => 'required|string',
            'phone' => 'sometimes|nullable|string|min:6|max:30',
            'email' => 'sometimes|nullable|email|max:100|unique:users',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,gif,png,jpg|max:2048',
            'address' => 'required|string|min:6|max:120',
            'bg_id' => 'sometimes|nullable',
            'state_id' => 'sometimes|nullable',
            'lga_id' => 'sometimes|nullable',
            'nal_id' => 'required',
            'my_class_id' => 'sometimes|nullable',
            'section_id' => 'sometimes|nullable',
            'my_parent' => 'sometimes|nullable',
            'dorm_id' => 'sometimes|nullable',
            'previous_school'=> 'sometimes|nullable',
            'status'=> 'sometimes|nullable',
            'college_id'=>'sometimes|nullable',
            'major'=> 'sometimes|nullable',
            'minor'=> 'sometimes|nullable',
            'department_id'=> 'sometimes|nullable',
            'level'=>'sometimes|nullable'
        ];
    }

    public function attributes()
    {
        return  [
            'section_id' => 'Section',
            'nal_id' => 'Nationality',
            'my_class_id' => 'Class',
            'dorm_id' => 'Dormitory',
            'state_id' => 'County',
            'lga_id' => 'Community',
            'bg_id' => 'Blood Group',
        ];
    }


}
