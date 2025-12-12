<?php

namespace App\Http\Controllers;

use App\Models\ClassType;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Major;
use App\Models\Mark;
use App\Models\Minor;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Qs;


class MarkSelectorController extends Controller
{
    protected $levels = ['Freshmen', 'Sophomore', 'Junior', 'Senior'];
    //
    public function planed(Request $request)
    {

        $course = DB::table('courses')
            ->where('college_id', $request->collegeId)
            ->where(function ($query) use ($request) {
                $query->where('department', $request->department)
                    ->orWhere('department', 0);
            })
            ->where(function ($query) use ($request) {
                $query->where('major', $request->major)
                    ->orWhere('major', 0);
            })
            ->where('level', $request->level)
            ->get();
        // }
        return response()->json($course);
    }

    public function exam(Request $request)
    {
        $semester = Qs::getSetting('Semester');
        $exam = Exam::where('year', $request->year)
            ->where('active',1)
            ->where('term', $semester)
            ->select('id', 'name', 'term')
            ->get();

        return response()->json($exam);
    }

    public function class()
    {
        $class = MyClass::where('active', 1)->select('id', 'name')->get();
        return response()->json($class);
    }


    public function section(Request $request)
    {
        $section = DB::table('sections')
            ->where('my_class_id', $request->class)
            ->select('id', 'name')
            ->orderBy('id', 'asc')
            ->get();
        return response()->json($section);
    }


    public function department(Request $request)
    {
        $departments = DB::table('class_types')
            ->where('college_id',$request->college)
            ->select('id','name', 'class_base')
            ->get();
        return response()->json($departments);
    }

    public function exam_department(Request $request)
    {
        if(Qs::userIsTeacher()) {
        $user = Auth::user()->id;

      $department = DB::table('enrolleds')
        ->join('subjects', 'enrolleds.subject_id', '=', 'subjects.id')
        ->join('class_types', 'subjects.department_id', '=', 'class_types.id')
        ->where('enrolleds.teacher_id', $user)
        ->select('class_types.id as id','class_types.name as name')
        ->groupBy('class_types.id','class_types.name')
        ->get();

        return response()->json($department);
    } else {
        $department = DB::table('class_types')
        ->select('id as id','name as name')
        ->get();
        return response()->json($department);
    }
}


    public function get_major(Request $request)
    {
        $majors = Major::where('department_id', $request->department)
            ->select('id','major')
            ->get();
        return response()->json($majors);
    }

    public function get_minor(Request $request)
    {
        $major = Major::where('major', 'like', '%' . $request->major . '%')->first();

        if (!$major) {
            $minor = NULL;
            return response()->json($minor);
        }

        // Then fetch the minors based on that major's ID
        $minors = Minor::where('major_id', $major->id)
            ->select('id', 'minor')
            ->get();

        return response()->json($minors);
    }


    public function subject(Request $request)
    {
        $term = Exam::find($request->exam);

        $user = Auth::user()->id;
        $sec = Section::find($request->section_id);

        $another_section = DB::table('sections')
            ->where('name', $sec->name)
            ->where('id', '!=', $request->section_id)
            ->select('id', 'name') // Add other fields if needed
            ->first(); // Remove limit(1) since first() already limits it

        if (Qs::userIsTeacher()) {

            $subjects = DB::table('courses')
                ->join('users', 'users.id', '=', 'courses.teacher_id')
                ->join('subjects', 'subjects.id', '=', 'courses.subject_id')
                ->where(function ($query) use ($request, $another_section, $term) {
                    $query->where('courses.section_id', $request->section_id)
                        ->orWhere(function ($subQuery) use ($another_section, $term) {
                            $subQuery->where('courses.section_id', $another_section->id);
                        });
                })
                ->where('courses.year', $request->year)
                ->where('courses.term_id', $term->term)
                ->where('courses.teacher_id', $user)
                ->whereRaw("FIND_IN_SET(?, courses.my_class_id)", [$request->class_id])
                ->select('subjects.name as name', 'courses.id as id', 'courses.session as session', 'users.name as teacher')
                ->get();
        } else {
            $subjects = DB::table('courses')
                ->join('users', 'users.id', '=', 'courses.teacher_id')
                ->join('subjects', 'subjects.id', '=', 'courses.subject_id')
                ->where(function ($query) use ($request, $another_section, $term) {
                    $query->where('courses.section_id', $request->section_id)
                        ->orWhere(function ($subQuery) use ($another_section, $term) {
                            $subQuery->where('courses.section_id', $another_section->id);
                        });
                })
                ->where('courses.year', $request->year)
                ->where('courses.term_id', $term->term)
                ->whereRaw("FIND_IN_SET(?,courses.my_class_id)", [$request->class_id])
                ->select('subjects.name as name', 'courses.id as id', 'courses.session as session', 'users.name as teacher')
                ->get();
        }
        return response()->json($subjects);
    }



    public function resit()
    {
        $d['years'] = DB::table('academic_year')->get();
        $d['departments'] = ClassType::where('class_base',1)->get();
        $d['levels'] = $this->levels;

        return view('resit.selector', $d);
    }

    public function resitgrade()
    {
        $d['years'] = DB::table('academic_year')->get();
    $d['departments'] = ClassType::where('class_base',1)->get();
        $d['levels'] = $this->levels;
        return view('pages.support_team.marks.indexresit', $d);
    }


    public function resitshow(Request $request)
    {

        // dd($request);
        $d['students'] = Mark::where('grade_get', '=', 0)
            ->where('year', $request->year)
            ->where('exam_id', $request->exam_id)
            ->where(function ($query) use ($request) {
                foreach ($request->subject_id as $subject) {
                    $query->orWhere('subject_id', $subject);
                }
            })
            ->get();
        $d['session'] = [];
        foreach ($request->subject_id as $subject) {
            $d['session'][] = Course::where('id', $subject)->first()->session;
        }
        // $d['cohort'] = DB::table('sections')->where('id', $request->section_id)->first()->name;
        $d['years'] = DB::table('academic_year')->get();
        $d['subject'] = Course::where('id', $request->subject_id[0])->first()->subject->name;
        $d['year'] = $request->year;
        $d['exam'] = Exam::find($request->exam_id)->name;

        // dd($d['exam']);
        return view('resit.view', $d);
    }




    public function clinical_subject(Request $request)
    {
        $term = Exam::find($request->exam);

        $user = Auth::user()->id;
        $sec = Section::find($request->section_id);

        $another_section = DB::table('sections')
            ->where('name', $sec->name)
            ->where('id', '!=', $request->section_id)
            ->select('id', 'name') // Add other fields if needed
            ->first(); // Remove limit(1) since first() already limits it

        if (Qs::userIsTeacher()) {

            $subjects = DB::table('courses')
                ->join('users', 'users.id', '=', 'courses.teacher_id')
                ->join('subjects', 'subjects.id', '=', 'courses.subject_id')
                ->where('subjects.clinical', 1)
                ->where(function ($query) use ($request, $another_section, $term) {
                    $query->where('courses.section_id', $request->section_id)
                        ->orWhere(function ($subQuery) use ($another_section, $term) {
                            $subQuery->where('courses.section_id', $another_section->id);
                        });
                })
                ->where('courses.year', $request->year)
                ->where('courses.term_id', $term->term)
                ->where('courses.teacher_id', $user)
                ->whereRaw("FIND_IN_SET(?, courses.my_class_id)", [$request->class_id])
                ->select('subjects.name as name', 'courses.id as id', 'courses.session as session', 'users.name as teacher')
                ->get();
        } else {
            $subjects = DB::table('courses')
                ->join('users', 'users.id', '=', 'courses.teacher_id')
                ->join('subjects', 'subjects.id', '=', 'courses.subject_id')
                ->where('subjects.clinical', 1)
                ->where(function ($query) use ($request, $another_section, $term) {
                    $query->where('courses.section_id', $request->section_id)
                        ->orWhere(function ($subQuery) use ($another_section, $term) {
                            $subQuery->where('courses.section_id', $another_section->id);
                        });
                })
                ->where('courses.year', $request->year)
                ->where('courses.term_id', $term->term)
                ->whereRaw("FIND_IN_SET(?,courses.my_class_id)", [$request->class_id])
                ->select('subjects.name as name', 'courses.id as id', 'courses.session as session', 'users.name as teacher')
                ->get();
        }
        return response()->json($subjects);
    }


    public function getSubjectsByLevel(Request $request)
        {
            $level = $request->level;
            $subjects = Subject::where('level', $level)->get(['id', 'name']);
            return response()->json($subjects);
        }
}
