<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Models\ClassType;
use App\Models\Course;
use App\Models\Enrolled;
use App\Models\Mark;
use App\Models\PaymentRecord;
use App\Models\StudentRecord;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class StudentPlanningController extends Controller
{
    protected $year,$term, $enrolled_courselist;

    public function __construct()
    {
        $this->year = Qs::getSetting('current_session');
        $this->term = Qs::getSetting('Semester');
        $this->enrolled_courselist = [];
    }
    //

    public function index()
    {
        $userId = Auth::id();
        $department = ClassType::all();
        $paid = PaymentRecord::where('student_id', $userId)
            ->whereHas('payment', fn($q) => $q->where('year', $this->year)->where('term_id', $this->term)->where('title', 'Registration'))
            ->first()->paid ?? 0;
    
        $planned = Enrolled::where('user_id', $userId)
            ->where('term_id', $this->term)
            ->where('year', $this->year)
            ->get();
    
            $studentRecord = StudentRecord::where('user_id', $userId)->first();
            $defaultDeptId = $studentRecord->department_id;
            $defaultLevel = $studentRecord->level ?? 'Freshmen';
            $status = $studentRecord->status;
    
        
    
        return view('pages.support_team.courses.list', [
            'departments' => $department,
            'paid' => $paid,
            'planned' => $planned,
            'planningOpen' => Qs::getSetting('planning_open') == 1,
            'enrolled_courselist' => $this->enrolled_courselist, // <- pass to view
            'defaultDeptId' => $defaultDeptId,
            'status' => $status,
            'defaultLevel' => $defaultLevel,
        ]);
    }
    

public function getCourses(Request $request)
{
    
    $level = $request->level;
    $department = $request->department;

    $this->enrolled_courselist = Course::with(['teacher', 'subject'])
        ->where('year', $this->year)
        ->where('term_id', $this->term)
        ->when($level, fn($q) => $q->where('level', $level))
        ->when($department, fn($q) => $q->whereRaw("FIND_IN_SET(?, department_id)", [$department]))
        ->paginate(5);

        return Response::json($this->enrolled_courselist);
}

public function enroll(Request $request)
{
    $userId = Auth::id();
    $studentRecord = StudentRecord::where('user_id', $userId)->first();

    if (!$studentRecord) {
        return Response::json(['message' => 'Student record not found.'], 404);
    }

    $course = Course::find($request->course_id);
    if (!$course) {
        return Response::json(['message' => 'Course not found.'], 404);
    }

    $existing = Enrolled::where('subject_id', $course->subject_id)
        ->where('user_id', $userId)
        ->where('year', $this->year)
        ->where('term_id',$this->term)
        ->first();

    if ($existing) {
        return Response::json(['message' => 'Already enrolled.'], 409);
    }

    $subject = Subject::find($course->subject_id);
    if (!$subject) {
        return Response::json(['message' => 'Subject not found.'], 404);
    }

    // ✅ Check prerequisite only if it exists
    if ($subject->prerequisite_id) {
        $prerequisiteSubjectId = $subject->prerequisite_id;

        // ✅ Check if student has any course taken under that subject
        $prerequisiteCourse = Course::where('subject_id', $prerequisiteSubjectId)
            ->orderByDesc('year') // if many, take the latest
            ->first();

        if (!$prerequisiteCourse) {
            return Response::json(['message' => 'Prerequisite course not found.'], 404);
        }

        // $semester_gpa = Mark::

        // ✅ Check student's marks (assuming grade_get >= 2 means pass)
        $passed = Mark::where('student_id', $userId)
            ->where('subject_id', $prerequisiteSubjectId)
            ->where('grade_get', '>=', 2) // Adjust grade logic as needed
            ->exists();

        if (!$passed) {
            return Response::json([
                'message' => 'You must pass the prerequisite course before enrolling.'
            ], 403);
        }
    }

    if ($course->total >= $course->capacity) {
        return Response::json(['message' => 'Session full.'], 422);
    }

    $course->increment('total');

    Enrolled::create([
        'course_id' => $course->id,
        'subject_id' => $course->subject_id,
        'teacher_id' => $course->teacher_id,
        'user_id' => $userId,
        'term_id' => $course->term_id,
        'session' => $course->session,
        'year' => $course->year,
        'time_from' => $course->time_from,
        'time_to' => $course->time_to,
        'day' => is_array($course->day) ? implode(',', $course->day) : $course->day,
        'room' => $course->room,
    ]);

    return Response::json(['message' => 'Course added successfully.'], 200);
}


public function dropCourse(Request $request)
{
    $courseId = $request->input('course_id');
    $user = Auth::user();
    
    try {
        // Find the enrollment record
        $enrollment = Enrolled::where('id', $courseId)
                            ->where('user_id', $user->id)
                            ->first();
        
        if (!$enrollment) {
            return response()->json([
                'message' => 'Course not found or you are not enrolled in this course',
                'type' => 'error'
            ], 404);
        }
    
        // Find the associated course
        $course = Course::find($enrollment->course_id);
        
        // Delete the enrollment
        $deleted = $enrollment->delete();
        
        if ($deleted && $course) {
            $course->decrement('total', 1);
            
            return response()->json([
                'message' => 'Course dropped successfully',
                'type' => 'success'
            ]);
        }
        
        return response()->json([
            'message' => 'Failed to drop course',
            'type' => 'error'
        ], 400);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while dropping the course: ' . $e->getMessage(),
            'type' => 'error'
        ], 500);
    }
}


public function plannedCourses()
{
    $planned = Enrolled::where('user_id',Auth::id())->where('term_id',$this->term)->where('year',$this->year)->get();
    
  return view('components.planned-courses-rows', compact('planned'));
}

public function courselist(Request $request)
{
    $level = $request->level;
    $department = $request->department;

    $enrolled_courselist = Course::with(['teacher', 'subject'])
            ->where('year', $this->year)
            ->where('term_id', $this->term)
            ->when($level, fn($q) => $q->where('level', $level))
            ->when($department, fn($q) => $q->whereRaw("FIND_IN_SET(?, department_id)", [$department]))
            ->get()
            ->sortBy(function ($course) {
                return $course->subject->name ?? '';
            })->values(); 


  
    return view('components.enrolled-courses-rows', compact('enrolled_courselist'));
}

}
