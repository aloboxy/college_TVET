<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrolled;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Exam;
use DB;

class CoursePlanningController extends Controller
{
    //

    public function index()
    {
        $years = DB::table('academic_year')->get();
        return view('pages.support_team.planning.selector', compact('years'));
    }
    public function enrolled(Request $request)
    {
        $enrolled = Enrolled::with('student', 'user')
            ->where('year', $request->year)
            ->where('term_id', $request->exam_id)
            ->get()
            ->unique('user_id');

        $my_class = MyClass::find($request->my_class_id);
        $sem = $request->exam_id;

        $years = $request->year;


        // Retrieve all students
        $allStudents = StudentRecord::with('user', 'my_class', 'section')->where('my_class_id', $request->my_class_id)->where('status', 1)->get();

        // Get user IDs from both collections
        $enrolledUserIds = $enrolled->pluck('user_id')->toArray();
        $allStudentUserIds = $allStudents->pluck('user_id')->toArray();

        // Find students not enrolled by comparing user IDs
        $not_enrolledUserIds = array_diff($allStudentUserIds, $enrolledUserIds);

        // Filter the original collection to get the not enrolled students
        $not_enrolled = $allStudents->whereIn('user_id', $not_enrolledUserIds);

        // dd($not_enrolled, $enrolled);

        return view('pages.support_team.planning.show', compact('enrolled', 'not_enrolled', 'years', 'my_class', 'sem'));
    }
}
