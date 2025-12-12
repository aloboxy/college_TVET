<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Models\Enrolled;
use DB;

class StaffCourseAddController extends Controller
{

    public function subject(Request $request)
    {
        // Fetch students who are not enrolled in the specified course
        $students_to_enrolled = StudentRecord::with(['user:id,name', 'enrolledCourses'])
            ->where('status', 1)
            ->where('my_class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereDoesntHave('enrolledCourses', function ($query) use ($request) {
                $query->where('course_id', $request->subject_id);
            })
            ->select('adm_no', 'user_id') // Select only necessary fields from student_records
            ->get();

        // Fetch students who are already enrolled in the specified course
        $student_enrolled = Enrolled::with(['student:id,adm_no', 'user:id,name'])
            ->where('year', $request->year)
            ->where('my_class_id', $request->class_id)
            ->where('course_id', $request->subject_id)
            ->where('section_id', $request->section_id)
            ->orderBy('user_id', 'ASC')
            ->select('user_id', 'my_class_id', 'course_id', 'section_id') // Select fields needed for filtering
            ->get();

        return response()->json([
            'students_to_enroll' => $students_to_enrolled,
            'student_enrolled' => $student_enrolled,
        ]);
    }

}
