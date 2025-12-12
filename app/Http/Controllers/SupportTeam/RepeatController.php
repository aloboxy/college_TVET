<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\ClassType;
use App\Models\Mark;
use App\Models\StudentRecord;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepeatController extends Controller
{
  protected $my_class, $exam, $student, $year, $user, $mark,
    $levels = ['Freshmen', 'Sophomore', 'Junior', 'Senior'];

    public function __construct(MyClassRepo $my_class, ExamRepo $exam, StudentRepo $studentRepo)
    {
        $this->exam = $exam;
        $this->student = $studentRepo;
        $this->my_class = $my_class;
        $this->year = Qs::getSetting('current_session');
    }

    public function index()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['departments'] = ClassType::all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['years'] = DB::table('academic_year')->get();
        $d['selected'] = false;
        $d['levels'] = $this->levels;
        $d['planning'] = Qs::getSetting('Close_Grade_Entry');

        return view('pages.support_team.repeat.index', $d);
    }

public function selector(Request $request)
{
    $validated = $request->validate([
        'year' => 'required',
        'exam_id' => 'required',
        'department_id' => 'required',
        'level' => '',
        'section_id' => '',
        'num_sub' => 'required|numeric',
    ]);

    $year = $validated['year'];
    $exam_id = intval($validated['exam_id']);
    $department_id = intval($validated['department_id']);
    $level = $validated['level'];
    $section_id = $validated['section_id'];
    $num_sub = intval($validated['num_sub']);

    // Get all students in department + level
    if($section_id == ''){
        $students_all = StudentRecord::where('department_id', $department_id)
        ->where('level', $level)
        ->get();
    }else{
        $students_all = StudentRecord::where('department_id', $department_id)
        ->where('section_id', $section_id)
        ->get();
    }

    $students = [];

    foreach ($students_all as $student) {
        // Get the failed marks for the student
        $failed_marks = Mark::where('student_id', $student->user_id)
            ->where('exam_id', $exam_id)
            ->where('year', $year)
            ->where(function ($q) {
                $q->where('grade_get', 0)
                  ->orWhereNull('grade_get');
            })
            ->get();

        $failed_count = $failed_marks->count();
 
        if ($failed_count >= $num_sub) {
            // Attach additional info to the student object
            $student->failed_count = $failed_count;

    
            $failed_subjects = $failed_marks->map(function ($mark) {
                return optional($mark->course->subject)->name ?? 'Unknown Subject';
            })->toArray();

            $student->failed_subjects = $failed_subjects;

            $students[] = $student;
        }
    }



    return view('pages.support_team.repeat.show', compact('students'));
}


}
