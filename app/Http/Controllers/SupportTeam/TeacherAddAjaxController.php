<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\{Enrolled, Course, User, Exam, ClassType, StudentRecord};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TeacherAddAjaxController extends Controller
{
    public function index()
    {
        $departments = ClassType::all();
        $years = DB::table('academic_year')->get();
        $levels = ['Freshmen', 'Sophomore', 'Junior', 'Senior'];

        return view('pages.support_team.teacheradd.index',compact('departments','years','levels'));
    }
    
    public function getEnrolledStudents(Request $request)
    {        
        $students = DB::table('enrolleds')
            ->join('users', 'enrolleds.user_id', '=', 'users.id')
            ->where('term_id', $request->exam)
            ->where('year', $request->year)
            ->where('course_id', $request->subject)
            ->select('users.name as name', 'users.username as adm_no', 'users.id as id')
            ->get();
        return response()->json($students->toArray());
    }

    public function getUnenrolledStudents(Request $request)
    {
     
        $students = DB::table('users')
                ->join('student_records', 'users.id', '=', 'student_records.user_id')
                ->where('users.status', '=',1)
                ->whereNotExists(function ($query) use ($request) {
                    $query->select(DB::raw(1))
                        ->from('enrolleds')
                        ->whereColumn('enrolleds.user_id', 'users.id')
                        ->where('enrolleds.term_id', $request->exam)
                        ->where('enrolleds.year', $request->year)
                        ->where('enrolleds.course_id', $request->subject);
                })
                ->select('users.id as id', 'users.name as name', 'student_records.adm_no as adm_no')
                ->get();

                return response()->json($students->toArray());
    }

    public function enrollStudent(Request $request)
    {
        $course = Course::where('id', $request->subject)->first();
    
        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }
    
        // Check if student is already enrolled
        $existing = Enrolled::where('subject_id', $course->subject_id)
            ->where('user_id', $request->studentId)
            ->where('year', $request->year)
            ->where('term_id', $request->term_id) // Ensure this matches with your request input
            ->first();
    
        if ($existing) {
            return response()->json(['message' => 'Student already enrolled in this course.'], 409);
        }
    
        try {
            $day = is_array($course->day) ? implode(',', $course->day) : $course->day;
    
            DB::transaction(function () use ($request, $course, $day) {
               Enrolled::create([
                    'user_id'     => $request->studentId,
                    'course_id'   => $course->id,
                    'teacher_id'  => $course->teacher_id,
                    'time_from'   => $course->time_from,
                    'time_to'     => $course->time_to,
                    'day'         => $day,
                    'room'        => $course->room,
                    'subject_id'  => $course->subject_id,
                    'session'     => $course->session,
                    'term_id'     => $request->term_id,
                    'year'        => $request->year,
                ]);
    
                $course->increment('total');
            });
    
            return response()->json([
                'success' => true,
                'message' => 'Student enrolled successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment failed: ' . $e->getMessage()
            ], 500);
        }
    }
    

    public function dropStudent(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
               
                $enrollment = Enrolled::where('user_id', $request->studentId)
                ->where('course_id', $request->enrollmentId)
                ->first();
                $enrollment->delete();
                Course::where('id', $enrollment->course_id)->decrement('total');
            });

            return response()->json([
                'success' => true,
                'message' => 'Student unenrolled successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unenrollment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSubjectByDepartment(Request $request)
    {
    //    $exam = DB::table('exams')->where('id',$request->exam)->select('term','id')->get();


            $subjects = DB::table('courses')
            ->join('users','users.id','=','courses.teacher_id')
            ->join('subjects','subjects.id','=','courses.subject_id')
            ->where('courses.year', $request->year)
            ->where('courses.term_id', $request->exam)
            ->where('courses.level', $request->level)
            ->whereRaw("FIND_IN_SET(?, courses.department_id)", [$request->department_id])
            ->select('courses.id as id', 'subjects.name as subject','users.name as teacher', 'courses.session as session')
            ->get();

            return response()->json($subjects->toArray());
       
    }

    public function getCourseByDepartment(Request $request)
    {
        $user = Auth::user();
        $term = DB::table('exams')
        ->where('id', $request->exam)
        ->select('term')
        ->first();
        if(Qs::userIsTeacher())
        {

            $courses = DB::table('courses')
            ->join('enrolleds','enrolleds.course_id','=','courses.id')
            ->join('subjects','subjects.id','=','courses.subject_id')
            ->join('users','users.id','=','courses.teacher_id')
            ->whereRaw("FIND_IN_SET(?, courses.department_id)", [$request->department_id])
            ->where('enrolleds.teacher_id',$user->id)
            ->where('enrolleds.year', $request->year)
            ->where('enrolleds.term_id', $term->term)
            ->select('courses.id as id', 'subjects.name as subject','courses.session as session','users.name as name')
            ->distinct('courses.id')
            ->get();

            return response()->json($courses->toArray());
        }
        else{
            $courses = DB::table('courses')
            ->join('enrolleds','enrolleds.course_id','=','courses.id')
            ->join('subjects','subjects.id','=','courses.subject_id')
            ->join('users','users.id','=','courses.teacher_id')
            ->whereRaw("FIND_IN_SET(?, courses.department_id)", [$request->department_id])
            ->where('enrolleds.year', $request->year)
            ->where('courses.level', $request->level)
            ->where('enrolleds.term_id', $term->term)
            ->select('courses.id as id', 'subjects.name as subject','courses.session as session','users.name as name')
            ->distinct('courses.id')
            ->get();
        
            return response()->json($courses->toArray());
        }
    }



    public function getClassByDepartment(Request $request)
    {
        $classes = DB::table('my_classes')
        ->where('class_type_id', $request->department_id)
        ->select('id','name')
        ->get();

        return response()->json($classes->toArray());
    }


    public function getClassSection(Request $request)
    {
        $sections = DB::table('sections')
        ->where('my_class_id', $request->class_id)
        ->select('id','name')
        ->get();

        return response()->json($sections->toArray());
    }

    public function getSectionByDepartment(Request $request)
    {
        $sections = DB::table('sections')
        ->join('my_classes','my_classes.id','=','sections.my_class_id')
        ->where('my_classes.class_type_id', $request->department_id)
        ->select('sections.id as id','sections.name as name')
        ->get();

        return response()->json($sections->toArray());
    }

    public function getStudentsByCourse(Request $request)
    {
        $students = DB::table('enrolleds')
        ->join('users', 'users.id', '=', 'enrolleds.user_id')
        ->where('enrolleds.course_id', $request->course_id)
        ->select('users.id as id', 'users.name as name')
        ->get();

        return response()->json($students->toArray());
    }

}
    
