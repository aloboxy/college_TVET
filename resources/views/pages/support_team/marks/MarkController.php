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

class MarkController extends Controller
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

        return view('pages.support_team.marks.index', $d);
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

    public function show($student_id, $year)
    {
        /* Prevent Other Students/Parents from viewing Result of others */
        if(Auth::user()->id != $student_id && !Qs::userIsTeamSAT() && !Qs::userIsMyChild($student_id, Auth::user()->id)){
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }

        if(Mk::examIsLocked() && !Qs::userIsTeamSA()){
            Session::put('marks_url', route('marks.show', [Qs::hash($student_id), $year]));

            if(!$this->checkPinVerified($student_id)){
                return redirect()->route('pins.enter', Qs::hash($student_id));
            }
        }

        if(!$this->verifyStudentExamYear($student_id, $year)){
            return $this->noStudentRecord();
        }
        $slug = Enrolled::where('user_id',$student_id)->first();
        $last = DB::table('exams')->where('year',$year)->get();
        $wh = ['student_id' => $student_id, 'year' => $year ];
        $d['marks'] = $this->exam->getMark($wh);
        $marks = $this->exam->getMark($wh);
        $d['exam_records'] = $exr = ExamRecord::where('student_id',$student_id)->where('year', $year)->get();
        $d['exams'] = $last;
        $d['sr'] = $this->student->getRecordInschool(['user_id' => $student_id])->first();
        $class= DB::table('marks')->where('student_id', $student_id)->where( 'year',$year )->first();
        $r_class = $class->my_class_id;
        $r_name = MyClass::find($r_class);
        $d['level'] = Str::before($r_name->name,'-');
        $d['my_class'] = $mc=StudentRecord::where('user_id', $student_id)->first();
        // $d['class_type'] = $this->my,_class->findTypeByClass($mc->id);
        $d['subjects'] = $slug->subject;
        $d['year'] = $year;
        $d['student_id'] = $student_id;
        $d['skills'] = $this->exam->getSkillByClassType() ?: NULL;
        //$d['ct'] = $d['class_type']->code;
        //$d['mark_type'] = Qs::getMarkType($d['ct']);

        foreach ($marks as $m)
        {
            if($m->grade_get == 0)
            {
                $d['remark'] = 'Resit';
            }
            if($m->grade_get == null)
            {
                $d['remark']= 'Resit';
            }
            else
            {
                $d['remark'] = '';
            }

        }

        return view('pages.support_team.marks.show.index', $d, );
    }

    public function print_view($student_id, $exam_id, $year)
    {
        /* Prevent Other Students/Parents from viewing Result of others */
        if(Auth::user()->id != $student_id && !Qs::userIsTeamSA() && !Qs::userIsMyChild($student_id, Auth::user()->id)){
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }

        if(Mk::examIsLocked() && !Qs::userIsTeamSA()){
            Session::put('marks_url', route('marks.show', [Qs::hash($student_id), $year]));

            if(!$this->checkPinVerified($student_id)){
                return redirect()->route('pins.enter', Qs::hash($student_id));
            }
        }

        if(!$this->verifyStudentExamYear($student_id, $year)){
            return $this->noStudentRecord();
        }
        $slug = Enrolled::where('user_id',$student_id)->first();

        $wh = ['student_id' => $student_id, 'exam_id' => $exam_id, 'year' => $year ];
        $d['marks'] = $mks = $this->exam->getMark($wh);
        $d['exr'] = $exr = $this->exam->getRecord($wh)->first();
        $studentclass = StudentRecord::where('user_id', $student_id)->first();
        $studclas = $studentclass->my_class_id;
        $d['my_class'] = $mc = $this->my_class->find($exr->my_class_id);
        $d['section_id'] = $exr->section_id;
        $d['ex'] = $exam = $this->exam->find($exam_id);
        $d['tex'] = 'tex'.$exam->term;
        $d['sr'] = $sr =$this->student->getRecord(['user_id' => $student_id])->first();
        $d['class_type'] = $this->my_class->findTypeByClass($studclas);
        $d['subjects'] = $this->my_class->findSubjectByClass($studclas);
        $d['ct'] = $ct = $d['class_type']->code;
        $d['year'] = $year;
        $d['student_id'] = $student_id;
        $d['exam_id'] = $exam_id;

        $d['skills'] = $this->exam->getSkillByClassType() ?: NULL;
        $d['s'] = Setting::all()->flatMap(function($s){
            return [$s->type => $s->description];
        });

        //$d['mark_type'] = Qs::getMarkType($ct);

        foreach ($mks->where('grade_get','=',0) as $m)
        {
            if($m->grade_get == 0)
            {
                $d['remark'] = 'Resit';
            }
            if($m->grade_get == null)
            {
                $d['remark']= 'Resit';
            }
            else
            {
                $d['remark'] = '';
            }
        }

        return view('pages.support_team.marks.print.index', $d);
    }

    public function selector(MarkSelector $req)
    {
        $sr = DB::table('sections')->where('id', $req->section_id)->first();
        if($sr->planning == 1)
        {
            return view('pages.support_team.marks.no');
        }

        else{

        $user = Auth::user()->id;
        $data = $req->only(['exam_id', 'my_class_id', 'section_id', 'subject_id', 'year']);
        $like = $req->only(['my_class_id', 'section_id', 'subject_id']);
        $d2 = $req->only(['exam_id']);
        $d = $req->only(['my_class_id', 'section_id']);
        $d['session'] = $data['year'] = $d2['year'] = $req->year;
        $like['session']= $data['year'];

        // dd($like);
        // $students = Enrolled::where('course_id',$req->course_id)->get();

        if(Qs::userIsTeacher())
        {
            $students = Enrolled::where('year', $req->year)->where('course_id',$req->subject_id)->where('teacher_id', $user)->get();
        }

        else{
            $students = Enrolled::where('year', $req->year)->where('course_id',$req->subject_id)->where('my_class_id',$req->my_class_id)->get();
        }
        // dd($req->section_id);
        if($students->count() < 1){
            return back()->with('pop_warning', __('msg.nsec'));
        }


        foreach ($students as $s){
            $data['student_id'] = $d2['student_id'] = $s->user_id;
            $this->exam->createMark($data);
            $this->exam->createRecord($d2);
        }

        return redirect()->route('marks.manage', [$req->exam_id, $req->my_class_id, $req->section_id, $req->subject_id, $req->year]);
        }
    }

    public function manage($exam_id, $class_id, $section_id, $subject_id, $year)
    {
        $sr = DB::table('sections')->where('id', $section_id)->first();
        if($sr->planning == 1)
        {
            return view('pages.support_team.marks.no');
        }

        else{
        $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $year];

        $d['marks'] = Mark::where('exam_id', $exam_id)->where('subject_id', $subject_id)->where('year', $year)->where('my_class_id',$class_id)->get();
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

        return view('pages.support_team.marks.manage', $d);
    }
    }

    public function update(Request $req, $exam_id, $class_id, $section_id, $subject_id, $year)
    {
        $user = Auth::user()->id;
        // $students = Enrolled::where('subject_id',$req->subject_id)->where('teacher_id', $user)->get();

        $p = ['exam_id' => $exam_id,'subject_id' => $subject_id,'section_id'=>$section_id,'year' => $year];

        $d = $d3 = $all_st_ids = [];

        $exam = $this->exam->find($exam_id);
        $marks = $this->exam->getMark($p);
        $class_type = $this->my_class->findTypeByClass($class_id);

        $mks = $req->all();

        /** Test, Exam, Grade **/
        foreach($marks->sortBy('user.name') as $mk)
        {
            $all_st_ids[] = $mk->student_id;


                $d['t1'] = $t1 = $mks['t1_'.$mk->id];
                $d['t2'] = $t2 = $mks['t2_'.$mk->id];
                $d['s1'] = $s1 = $mks['s1_'.$mk->id];
                $d['tca'] = $tca = $t1 + $t2 + $s1;
                $d['exm'] = $exm = $mks['exm_'.$mk->id];

            /** SubTotal Grade, Remark, Cum, CumAvg**/

            $d['tex'.$exam->term] = $total = $tca + $exm;

            if($total > 100){
                $d['tex'.$exam->term] = $d['t1'] = $d['t2'] = $d['t3'] = $d['s1'] = $d['t4'] = $d['tca'] = $d['exm'] = NULL;
            }
         /*   if($exam->term < 3){
                $grade = $this->mark->getGrade($total, $class_type->id);
            }
            if($exam->term == 3){
                $d['cum'] = $this->mark->getSubCumTotal($total, $st_id, $subject_id, $class_id, $this->year);
                $d['cum_ave'] = $cav = $this->mark->getSubCumAvg($total, $st_id, $subject_id, $class_id, $this->year);
                $grade = $this->mark->getGrade(round($cav), $class_type->id);
            }*/

           $ls = Grade::where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first();
           if($ls != null){
            $d['grade_id'] = $ls->id;
            $rg = 0;
            $gt = round($ls->grade, 1);

            if($ls->grade == 0)
            {
                $rg = 1;
            }

            if($ls->grade != 0)
            {
                $rg = 0;
            }
            // dd($gt);

             DB::table('marks')->where('id', $mk->id)->update([
                'grade_get'=>$gt,
                'failed' =>$rg
             ]);
             }
             $grade =DB::table('grades')->where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first();
             $d['grade_id'] = $grade ? $grade->id : NULL;
            // DB::table('marks')->find($mk->id)->update($d);
            $this->exam->updateMark($mk->id, $d);

        }

        /** Sub Position Begin  **/

        // foreach($marks->sortBy('user.name') as $mk)
        // {

        //     // $d2['sub_pos'] = $this->mark->getSubPos($mk->student_id, $exam,  $subject_id, $this->year);

        //     $this->exam->updateMark($mk->id, $d2);
        // }
        $exam = DB::table('exams')->find($exam_id);
        $tex = 'tex'.$exam->term;



        /*Sub Position End*/

        /* Exam Record Update */

        unset( $p['subject_id'] );

        foreach ($all_st_ids as $st_id) {

            $p['student_id'] =$st_id;
            // $d3['total'] = $this->mark->getExamTotalTerm($exam, $st_id,  $this->year);
            // $d3['ave'] = $this->mark->getExamAvgTerm($exam, $st_id, $this->year);
            // $d3['class_ave'] = $this->mark->getClassAvg($exam, $this->year);
            // $d3['pos'] = $this->mark->getPos($st_id, $exam,  $this->year);

        $ct = DB::table('marks')->where('student_id', $st_id)->where('year', $year)->where('exam_id', $exam_id)->where($tex, '>', 0)->sum('grade_get');

        $a = DB::table('marks')->where('student_id', $st_id)->where('year', $year)->where('exam_id', $exam_id)->where($tex, '>', 0)->select($tex)->avg('grade_get');

        $avg = round($a, 3);

        DB::table('exam_records')->where('student_id', $st_id)->where('year', $year)->where('exam_id', $exam_id)->update([
           'total'=> $ct,
           'ave'=>$avg
        ]);


        }
        /*Exam Record End*/

       return Qs::jsonUpdateOk();
    }

    public function batch_fix()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['selected'] = false;

        return view('pages.support_team.marks.batch_fix', $d);
    }

    public function batch_update(Request $req): \Illuminate\Http\JsonResponse
    {
        $exam_id = $req->exam_id;
        $class_id = $req->my_class_id;
        $section_id = $req->section_id;

        $w = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'year' => $this->year];

        $exam = $this->exam->find($exam_id);
        $exrs = DB::table('exam_records')->where('exam_id', $exam_id)->where('year', $this->year)->get();
        $marks = DB::table('marks')->where('exam_id', $exam_id)->where('year', $this->year)->get();

        /** Marks Fix Begin **/

        $class_type = $this->my_class->findTypeByClass($class_id);
        $tex = 'tex'.$exam->term;

        foreach($marks as $mk){

            $total = $mk->$tex;
            $d['grade_id'] = Grade::where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first();
           $grd =  DB::table('grades')->where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first();

            $d['grade_get'] = $grd->grade;
            /*      if($exam->term == 3){
                      $d['cum'] = $this->mark->getSubCumTotal($total, $mk->student_id, $mk->subject_id, $class_id, $this->year);
                      $d['cum_ave'] = $cav = $this->mark->getSubCumAvg($total, $mk->student_id, $mk->subject_id, $class_id, $this->year);
                      $grade = $this->mark->getGrade(round($mk->cum_ave), $class_type->id);
                  }*/

            $this->exam->updateMark($mk->id, $d);
        }

        /* Marks Fix End*/

        /** Exam Record Update  **/
        foreach($exrs as $exr){

            $st_id = $exr->student_id;

            $d3['total'] = $this->mark->getExamTotalTerm($exam, $st_id, $this->year);
            $d3['ave'] = $this->mark->getExamAvgTerm($exam, $st_id, $section_id, $this->year);
            // $d3['class_ave'] = $this->mark->getClassAvg($exam, $this->year);
            // $d3['pos'] = $this->mark->getPos($st_id, $exam,  $this->year);

            $this->exam->updateRecord(['id' => $exr->id], $d3);
        }

        /** END Exam Record Update END **/

        return Qs::jsonUpdateOk();
    }

    public function comment_update(Request $req, $exr_id)
    {
        $d = Qs::userIsTeamSA() ? $req->only(['t_comment', 'p_comment']) : $req->only(['t_comment']);

        $this->exam->updateRecord(['id' => $exr_id], $d);
        return Qs::jsonUpdateOk();
    }

    public function skills_update(Request $req, $skill, $exr_id)
    {
        $d = [];
        if($skill == 'AF' || $skill == 'PS'){
            $sk = strtolower($skill);
            $d[$skill] = implode(',', $req->$sk);
        }

        $this->exam->updateRecord(['id' => $exr_id], $d);
        return Qs::jsonUpdateOk();
    }

    public function bulk($class_id = NULL, $section_id = NULL)
    {
        $d['my_classes'] = $this->my_class->all();
        $d['selected'] = false;

        if($class_id && $section_id){
            $d['sections'] = $this->my_class->getAllSections()->where('my_class_id', $class_id);
            $d['students'] = $st = $this->student->getRecordInschool(['my_class_id' => $class_id, 'section_id' => $section_id])->get()->sortBy('user.name');
            if($st->count() < 1){
                return redirect()->route('marks.bulk')->with('flash_danger', __('msg.srnf'));
            }
            $d['selected'] = true;
            $d['my_class_id'] = $class_id;
            $d['section_id'] = $section_id;
        }

        return view('pages.support_team.marks.bulk', $d);
    }

    public function bulk_select(Request $req)
    {
        return redirect()->route('marks.bulk', [$req->my_class_id, $req->section_id]);
    }

    public function tabulation($exam_id = NULL, $class_id = NULL, $section_id = NULL)
    {
        $d['my_classes'] = $this->my_class->all();
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['selected'] = FALSE;

        if($class_id && $exam_id && $section_id){

            $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $this->year];

            $sub_ids = $this->mark->getSubjectIDs($wh);
            $st_ids = $this->mark->getStudentIDs($wh);
            $et = Exam::find($exam_id);
            $et_term = $et->term;

            if(count($sub_ids) < 1 OR count($st_ids) < 1) {
                return Qs::goWithDanger('marks.tabulation', __('msg.srnf'));
            }

            $d['subjects'] = Course::where('my_class_id',$class_id)->where('section_id', $section_id)->where('term_id',$et_term)->with('subject')->get()->sortBy('subject.name');
            $d['students'] = $this->student->getRecordByUserIDs($st_ids)->get()->sortBy('user.name');
            $d['sections'] = $this->my_class->getAllSections();

            $d['selected'] = TRUE;
            $d['my_class_id'] = $class_id;
            $d['section_id'] = $section_id;
            $d['exam_id'] = $exam_id;
            $d['year'] = $this->year;
            $d['marks'] = $mks = $this->exam->getMark($wh);
            $d['exr'] = $exr = $this->exam->getRecord($wh);

            $d['my_class'] = $mc = $this->my_class->find($class_id);
            $d['section']  = $this->my_class->findSection($section_id);
            $d['ex'] = $exam = $this->exam->find($exam_id);
            $d['tex'] = 'tex'.$exam->term;
            //$d['class_type'] = $this->my_class->findTypeByClass($mc->id);
            //$d['ct'] = $ct = $d['class_type']->code;
        }

        return view('pages.support_team.marks.tabulation.index', $d);
    }

    public function print_tabulation($exam_id, $class_id, $section_id)
    {
        $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $this->year];

        $sub_ids = $this->mark->getSubjectIDs($wh);
        $st_ids = $this->mark->getStudentIDs($wh);
        $et = Exam::find($exam_id);
        $et_term = $et->term;

        if(count($sub_ids) < 1 OR count($st_ids) < 1) {
            return Qs::goWithDanger('marks.tabulation', __('msg.srnf'));
        }

        $d['subjects'] = Course::where('my_class_id',$class_id)->where('section_id', $section_id)->where('term_id',$et_term)->with('subject')->get()->sortBy('subject.name');
        $d['students'] = $this->student->getRecordByUserIDs($st_ids)->get()->sortBy('user.name');

        $d['my_class_id'] = $class_id;
        $d['exam_id'] = $exam_id;
        $d['year'] = $this->year;
        $wh = ['exam_id' => $exam_id, 'my_class_id' => $class_id];
        $d['marks'] = $mks = Mark::all();
        $d['exr'] = $exr = $this->exam->getRecord($wh);

        $d['my_class'] = $mc = $this->my_class->find($class_id);
        $d['section']  = $this->my_class->findSection($section_id);
        $d['ex'] = $exam = $this->exam->find($exam_id);
        $d['tex'] = 'tex'.$exam->term;
        $d['s'] = Setting::all()->flatMap(function($s){
            return [$s->type => $s->description];
        });
        //$d['class_type'] = $this->my_class->findTypeByClass($mc->id);
        //$d['ct'] = $ct = $d['class_type']->code;

        return view('pages.support_team.marks.tabulation.print', $d);
    }

    public function tabulation_select(Request $req)
    {
        return redirect()->route('marks.tabulation', [$req->exam_id, $req->my_class_id, $req->section_id]);
    }

    protected function verifyStudentExamYear($student_id, $year = null)
    {
        $years = $this->exam->getExamYears($student_id);
        $student_exists = $this->student->exists($student_id);

        if(!$year){
            if($student_exists && $years->count() > 0)
            {
                $d =['years' => $years, 'student_id' => Qs::hash($student_id)];

                return view('pages.support_team.marks.select_year', $d);
            }

            return $this->noStudentRecord();
        }

        return ($student_exists && $years->contains('year', $year)) ? true  : false;
    }

    protected function noStudentRecord()
    {
        return redirect()->route('marks')->with('flash_danger', __('msg.srnf'));
    }

    protected function checkPinVerified($st_id)
    {
        return Session::has('pin_verified') && Session::get('pin_verified') == $st_id;
    }


    public function destroy($id)
    {


        $all= DB::table('marks')->find($id);
        $student_id = $all->student_id;
        $year = $all->year;
        $subject = $all->subject_id;
        $exam_id = $all->exam_id;
        $exam = DB::table('exams')->find($exam_id);
        $tex = 'tex'.$exam->term;

 //deleting grade
 DB::table('enrolleds')->where('user_id',$student_id)->where('course_id',$subject)->where('year',$year)->delete();

 //deleting mark
 DB::table('marks')->where('id',$id)->delete();

        $ct = DB::table('marks')->where('student_id', $student_id)->where('year', $year)->where('exam_id', $exam_id)->where($tex, '>', 0)->sum('grade_get');

        $a = DB::table('marks')->where('student_id', $student_id)->where('year', $year)->where('exam_id', $exam_id)->where($tex, '>', 0)->select($tex)->avg('grade_get');

        $avg = round($a, 3);

        DB::table('exam_records')->where('student_id', $student_id)->where('year', $year)->where('exam_id', $exam_id)->update([
           'total'=> $ct,
           'ave'=>$avg
        ]);

        return redirect()-> route('marks.show', [Qs::hash($student_id), $year]);

    }

}
