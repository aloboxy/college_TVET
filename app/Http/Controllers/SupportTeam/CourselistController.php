<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use Illuminate\Support\Str;
use App\Helpers\Mk;
use App\Http\Requests\Mark\MarkSelector;
use App\Models\Setting;
use App\Repositories\ExamRepo;
use App\Repositories\MarkRepo;
use App\Repositories\MyClassRepo;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrolled;
use App\Models\Exam;
use App\Models\ExamRecord;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CourselistController extends Controller
{
    protected $my_class, $exam, $student, $year, $user, $mark;

    public function __construct(MyClassRepo $my_class, ExamRepo $exam, StudentRepo $student, MarkRepo $mark)
    {
        $this->exam =  $exam;
        $this->mark =  $mark;
        $this->student =  $student;
        $this->my_class =  $my_class;
        $this->year =  Qs::getSetting('current_session');

       // $this->middleware('teamSAT', ['except' => ['show', 'year_selected', 'year_selector', 'print_view'] ]);
    }

    public function index()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['selected'] = false;

        return view('pages.support_team.lists.index', $d);
    }

    public function year_selector($student_id)
    {
       return $this->verifyStudentExamYear($student_id);
    }

    public function year_selected(Request $req, $student_id)
    {
        if(!$this->verifyStudentExamYear($student_id, $req->year)){
            return $this->noStudentRecord();
        }

        $student_id = Qs::hash($student_id);
        return redirect()->route('marks.show', [$student_id, $req->year]);
    }



    public function selector(MarkSelector $req)
    {


        $user = Auth::user()->id;
        $data = $req->only(['exam_id', 'my_class_id', 'section_id', 'subject_id', 'year']);
        $like = $req->only(['my_class_id', 'section_id', 'subject_id']);
        $d2 = $req->only(['exam_id']);
        $d = $req->only(['my_class_id', 'section_id']);
        $d['session'] = $data['year'] = $d2['year'] = $req->year;
        $like['session']= $data['year'];
        $term = DB::table('exams')->where('id',$req->exam_id)->first();
        // dd($term->term);
        // $students = Enrolled::where('course_id',$req->course_id)->get();

        if(Qs::userIsTeacher())
        {
            $students = DB::table('enrolleds')
            ->where('year', $req->year)
            ->where('course_id',$req->subject_id)
            ->where('teacher_id', $user)
            ->get();
        }

        else{
            $students = Enrolled::
            where('year', $req->year)
            ->where('course_id',$req->subject_id)
            ->get();
        }
        // dd($req->section_id);
        if($students->count() < 1){
            return back()->with('pop_warning', __('msg.nsec'));
        }

        return redirect()->route('courselist.manage', [$req->exam_id, $req->my_class_id, $req->section_id, $req->subject_id, $req->year]);

    }

    public function manage($exam_id, $class_id, $section_id, $subject_id, $year)
    {

        $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $year];

        $d['marks'] = Enrolled::
        where('course_id', $subject_id)
        ->where('year', $year)
        ->get();

        if($d['marks']->count() < 1)
        {
            return $this->noStudentRecord();
        }
        $d['my_class'] = DB::table('my_classes')->where('id', $class_id)->first();
        $d['m'] =  Course::where('id', $subject_id)->first();
        $d['exams'] = $this->exam->all();
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['subjects'] = $this->my_class->getAllSubjects();
        if(Qs::userIsTeacher()){
            $d['subjects'] = $this->my_class->findSubjectByTeacher(Auth::user()->id)->where('my_class_id', $class_id);
        }
        $d['year']= $year;
        $d['selected'] = true;
        $d['class_type'] = $this->my_class->findTypeByClass($class_id);

        return view('pages.support_team.lists.manage', $d);

    }
}
