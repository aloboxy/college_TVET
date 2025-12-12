<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrolledCourseController extends Controller
{
    //

    public function index()
    {
        $courses = DB::table('courses')->pluck("id");

        return view('pages.support_team.courses.list');
    }

    public function getClass(Request $request)
    {
        $classes = DB::table('my_classes')
                    ->where('my_class_id', $request->class_id)
                    ->pluck('name');
            return response()->json($classes);
    }

    public function getSemester(Request $request)
    {
        $semester = DB::table('courses')
                    ->where('term_id', $request->term_id)->pluck('id');
        return response()->json($semester);
    }

}
