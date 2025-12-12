<?php

namespace App\Http\Controllers\SupportTeam;
use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\ExamRecord;
use App\Models\Mark;
use App\Models\MyClass;
use App\Models\Setting;
use App\Models\StudentRecord;
use App\Repositories\ExamRepo;
use App\Repositories\MarkRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDF;

class GradeSheetLedger extends Controller
{
    //
    protected $my_class, $exam, $student, $year, $user, $mark,
    $levels = ['Freshmen', 'Sophomore', 'Junior', 'Senior'];

    public function __construct(MyClassRepo $my_class, ExamRepo $exam, StudentRepo $student, MarkRepo $mark)
    {
        $this->exam = $exam;
        $this->mark = $mark;
        $this->student = $student;
        $this->my_class = $my_class;
        $this->year = Qs::getSetting('current_session');

        // $this->middleware('teamSAT', ['except' => ['show', 'year_selected', 'year_selector', 'print_view'] ]);
    }

    public function ledger($student_id)
    {

        if (Auth::user()->id != $student_id && !Qs::userIsTeamSAT() && !Qs::userIsMyChild($student_id, Auth::user()->id))
        {
        return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
    }

// dd($student_id);
    $marks = $this->exam->getMark(['student_id' => $student_id]);
    $exam_records = ExamRecord::where('student_id', $student_id)->get();
    $studentclass = $sr = StudentRecord::where('user_id', $student_id)->first();

// dd($marks);
// dd($sr);
    // $sr = StudentRecord::where('user_id', $student_id)->first();


    if($sr->my_class_id == NUll)
    {
       $ggh= StudentRecord::where('user_id', $student_id)->first();
    }
    else
    {
        $ggh = MyClass::find($sr->my_class_id);
        $my_class = Str::before($sr->my_class->name, '-');
        $my_cohort = Str::after($sr->my_class->name, '-');
        $section_id = $sr->section_id;

    }
    $remark = $this->calculateGradeRemark($marks);
    $exam = Mark::join('exams', 'exams.id', '=', 'marks.exam_id')
        ->where('marks.student_id', $student_id)
        // ->where('exams.published',1)
        ->select('exams.id as id', 'exams.name as exam_name', 'exams.term', 'exams.year','exams.published as published')
        ->groupBy('exams.id', 'exams.name', 'exams.term', 'exams.year','exams.published')
        ->get();

        $credit_sum= Mark::join('courses', 'marks.subject_id', '=', 'courses.id')
        ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
        ->join('exams','exams.id','=','marks.exam_id')
        ->where('marks.student_id', $student_id)
        ->where('exams.published',1)
        ->selectRaw('SUM(subjects.credit) as total_credit')
        ->first();

    $s = Setting::all()->flatMap(function ($s) {
            return [$s->type => $s->description];
        });

        if($studentclass->my_class_id == null) {
            $studclas = $studentclass;
            $class_type = $studentclass->department_id;
        }
        else{
            $my_class=$mc = MyClass::find($studentclass->my_class_id);
            $level = Str::before($mc->name, '-');
            $class_type = $this->my_class->findTypeByClass($studentclass->my_class_id);
        }


    // dd($credit_sum->total_credit);
    $d = [
        'marks' => $marks,
        'exam_records' => $exam_records,
        'sr' => $sr,
        'student_id' => $student_id,
        'remark' => $remark,
        'my_class' => $my_class ?? null,
        'section_id' => $section_id ?? null,
        'exams' => $exam,
        'my_coho' => $my_cohort ?? null,
        'ggh' =>$ggh,
        'credit_sum' => $credit_sum->total_credit ?? 1,
        's' => $s,
        'studentclass' => $studentclass,
        'class_type' => $class_type,
        'level' => $level ?? null,
        'my_class' => $my_class ?? null,
        'my_class_id' => $sr->my_class_id ?? null,
        'studclas' => $studclas ?? null,

    ];
    return view('pages.support_team.marks.ledger', $d);

    }


    private function calculateGradeRemark($marks)
    {
        foreach ($marks as $mark) {
            if ($mark->grade_get == 0 || $mark->grade_get == null) {
                return 'Resit';
            }
        }
        return '';
    }
}
