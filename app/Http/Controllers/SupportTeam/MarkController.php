<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Models\ClassType;
use App\Models\PaymentRecord;
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
use App\Models\Subject;

class MarkController extends Controller
{
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

        // Granular Permissions
        // Granular Permissions
        $this->middleware('can:marks.view', ['only' => ['index']]);
        $this->middleware('can:marks.create', ['only' => ['selector', 'batch_fix']]); // selector creates records
        $this->middleware('can:marks.edit', ['only' => ['manage', 'update', 'batch_update']]);
        $this->middleware('can:marksheet.view', ['only' => ['download']]);
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

        return view('pages.support_team.marks.index', $d);
    }


    public function indexdownload()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['levels'] = $this->levels;
        $d['departments'] = ClassType::all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['years'] = DB::table('academic_year')->get();
        $d['selected'] = false;
        $d['planning'] = Qs::getSetting('Close_Grade_Entry');

        return view('pages.support_team.marks.indexdownload', $d);
    }

    public function year_selector($student_id)
    {
        $decoded = Qs::decodeHash($student_id);
        $student_id = $decoded ?: $student_id;

        if (Auth::user()->id != $student_id && !Auth::user()->can('marks.view') && !Qs::userIsMyChild($student_id, Auth::user()->id)) {
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }
        return $this->verifyStudentExamYear($student_id);
    }

    public function year_selected(Request $req, $student_id)
    {
        $decoded = Qs::decodeHash($student_id);
        $student_id = $decoded ?: $student_id;

        if (Auth::user()->id != $student_id && !Auth::user()->can('marks.view') && !Qs::userIsMyChild($student_id, Auth::user()->id)) {
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }
        if (!$this->verifyStudentExamYear($student_id, $req->year)) {
            return $this->noStudentRecord();
        }

        return redirect()->route('marks.show', [$student_id, $req->year]);
    }

    public function show($student_id, $year)
    {
        $decoded = Qs::decodeHash($student_id);
        $student_id = $decoded ?: $student_id;

        /* Prevent Other Students/Parents from viewing Result of others */
        if (Auth::user()->id != $student_id && !Auth::user()->can('marks.view') && !Qs::userIsMyChild($student_id, Auth::user()->id)) {
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }
        if (Mk::examIsLocked() && !Qs::userIsTeamSA()) {
            Session::put('marks_url', route('marks.show', [Qs::hash($student_id), $year]));

            if (!$this->checkPinVerified($student_id)) {
                return redirect()->route('pins.enter', Qs::hash($student_id));
            }
        }

        if (!$this->verifyStudentExamYear($student_id, $year)) {
            return $this->noStudentRecord();
        }

        $marks = $this->exam->getMark(['student_id' => $student_id, 'year' => $year]);
        $exam_records = ExamRecord::where('student_id', $student_id)->where('year', $year)->get();
        $sr = $this->student->getRecordInschool(['user_id' => $student_id])->first();
        $class = DB::table('marks')->where('student_id', $student_id)->where('year', $year)->first();
        $r_class = $class->my_class_id;

        // $sr = StudentRecord::where('user_id', $student_id)->first();
        $sec = $marks->first();
        $section_id = $sec->section_id;
        if($sr->my_class_id == NUll)
        {
           $ggh= StudentRecord::where('user_id', $student_id)->first();
        }
        else
        {
            $ggh = MyClass::find($r_class);
            $my_class = Str::before($sr->my_class->name, '-');
            $my_cohort = Str::after($sr->my_class->name, '-');

        }
        $remark = $this->calculateGradeRemark($marks);
        $exam = Mark::join('exams', 'exams.id', '=', 'marks.exam_id')
            ->where('exams.year', $year)
            ->where('marks.student_id', $student_id)
            ->select('exams.id as id', 'exams.name as exam_name', 'exams.term', 'exams.year')
            ->groupBy('exams.id', 'exams.name', 'exams.term', 'exams.year')
            ->get();

            $credit_sum= Mark::join('courses', 'marks.subject_id', '=', 'courses.id')
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->where('marks.year', $year)
            ->where('marks.student_id', $student_id)
            ->selectRaw('SUM(subjects.credit) as total_credit')
            ->first();





        // dd($credit_sum->total_credit);
        $d = [
            'marks' => $marks,
            'exam_records' => $exam_records,
            'sr' => $sr,
            'level' => $this->getStudentLevel($student_id, $year),
            'year' => $year,
            'student_id' => $student_id,
            'remark' => $remark,
            'my_class' => $my_class ?? null,
            'section_id' => $section_id ?? null,
            'exams' => $exam,
            'my_coho' => $my_cohort ?? null,
            'ggh' =>$ggh,
            'credit_sum' => $credit_sum->total_credit ?? 0,
        ];

        return view('pages.support_team.marks.show.index', $d);
    }




    private function getStudentLevel($student_id, $year)
    {
        // Assuming a method exists in StudentRepo to fetch the student's level
        return $this->student->getLevel($student_id, $year);
    }

     public function print_view($student_id, $exam_id, $year)
    {
        $decoded = Qs::decodeHash($student_id);
        $student_id = $decoded ?: $student_id;

        /* Prevent Other Students/Parents from viewing Result of others */
        if (Auth::user()->id != $student_id && !Auth::user()->can('marksheet.view') && !Qs::userIsMyChild($student_id, Auth::user()->id)) {
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }

        if (Mk::examIsLocked() && !Qs::userIsTeamSA()) {
            Session::put('marks_url', route('marks.show', [Qs::hash($student_id), $year]));

            if (!$this->checkPinVerified($student_id)) {
                return redirect()->route('pins.enter', Qs::hash($student_id));
            }
        }

        if (!$this->verifyStudentExamYear($student_id, $year)) {
            return $this->noStudentRecord();
        }
        $slug = Enrolled::where('user_id', $student_id)->first();
        $class = DB::table('marks')->where('student_id', $student_id)->where('year', $year)->first();
        $r_class = $class->my_class_id;


        $wh = ['student_id' => $student_id, 'exam_id' => $exam_id, 'year' => $year];
        $d['marks'] = $mks = $this->exam->getMark($wh);
        $d['exr'] = $exr = $this->exam->getRecord($wh)->first();
        $d['studentclass']= $studentclass = StudentRecord::where('user_id', $student_id)->first();


        if($studentclass->my_class_id == null) {
            $d['studclas']=$studclas =$studentclass ;
        }
        else{
            $r_class = $studentclass->my_class_id;
        $d['my_class'] = $mc = MyClass::find($r_class);
        $d['level'] = Str::before($mc->name, '-');
        }

        $credit_sum= Mark::join('courses', 'marks.subject_id', '=', 'courses.id')
        ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
        ->where('marks.exam_id', $exam_id)
        ->where('marks.student_id', $student_id)
        ->selectRaw('SUM(subjects.credit) as total_credit')
        ->first();

        $d['credit_sum'] = $credit_sum->total_credit ?? 0;

        $d['section_id'] = $exr->section_id;
        $d['ex'] = $exam = $this->exam->find($exam_id);
        $d['tex'] = 'tex' . $exam->term;
        $d['sr'] = $sr = $this->student->getRecord(['user_id' => $student_id])->first();
        if($studentclass->my_class_id == null) {
            $d['class_type'] = $studclas->department_id;
        }
        else{
        $d['class_type'] = $this->my_class->findTypeByClass($r_class);
        }
        // $d['subjects'] = $this->my_class->findSubjectByClass($studclas);
        // $d['ct'] = $ct = $d['class_type']->code;
        $d['year'] = $year;
        $d['student_id'] = $student_id;
        $d['exam_id'] = $exam_id;
        $d['remark'] = $this->calculateGradeRemark($mks);

        $d['skills'] = $this->exam->getSkillByClassType() ?: NULL;
        $d['s'] = Setting::all()->flatMap(function ($s) {
            return [$s->type => $s->description];
        });

        //$d['mark_type'] = Qs::getMarkType($ct);
        // dd($studclas);

        return view('pages.support_team.marks.print.index', $d);
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

    public function selector(MarkSelector $req)
    {
        $cur_year = Qs::getSetting('current_session');
        $planning = Qs::getSetting('planning_open');

        if ($planning == 1 && $req->year == $cur_year) {
            $d['years'] = DB::table('academic_year')->get();
            return view('pages.support_team.marks.no', $d);
        } else {

            $user = Auth::user()->id;
            $data = $req->only(['exam_id','department_id', 'subject_id', 'year']);
            $like = $req->only(['department_id', 'subject_id']);
            $d2 = $req->only(['exam_id']);
            $d = $req->only(['department_id']);
            $d['session'] = $data['year'] = $d2['year'] = $req->year;
            $like['session'] = $data['year'];
            $term = Qs::getSetting('Semester');
            // dd($req->all());
            // dd($term->term);
            // $students = Enrolled::where('course_id',$req->course_id)->get();

            if (Qs::userIsTeacher()) {
                $students = Enrolled::where('year', $req->year)
                    ->where('course_id', $req->subject_id)
                    ->where('teacher_id', $user)
                    ->get();
            } else {
                $students = Enrolled::where('year', $req->year)
                    ->where('course_id', $req->subject_id)
                    ->where('term_id', $term)
                    ->get();
            }
            // dd($req->section_id);
            if ($students->count() < 1) {
                return back()->with('pop_warning', __('msg.nsec'));
            }

            foreach ($students as $s) {
                $data['student_id'] = $d2['student_id'] = $s->user_id;
                // $data['section_id'] = $s->sectin_id;

                $student = StudentRecord::find($s->user_id);
                $check = DB::table('marks')
                    ->where('year', $req->year)
                    ->where('exam_id', $req->exam_id)
                    ->where('subject_id', $req->subject_id)
                    ->where('student_id', $s->user_id)
                    ->first();

                $exam_check = DB::table('exam_records')
                    ->where('year', $req->year)
                    ->where('exam_id', $req->exam_id)
                    ->where('student_id', $s->user_id)
                    ->first();
                //    dd($check->subject_id);
                if ($check == null) {
                    Mark::create([
                        'student_id' => $s->user_id,
                        'year' => $req->year,
                        'exam_id' => $req->exam_id,
                        'subject_id' => $req->subject_id,
                        'my_class_id' => $student->my_class_id ?? null,
                        'section_id' => $student->section_id ?? null,
                        'department_id' => $req->department_id ?? null,
                        'term_id' => $term
                    ]);

                    if ($exam_check == null) {
                        ExamRecord::create([
                            'student_id' => $s->user_id,
                            'exam_id' => $req->exam_id,
                            'my_class_id' => $student->my_class_id ?? null,
                            'section_id' => $student->section_id ?? null,
                            'year' => $req->year,
                            'department_id' => $req->department_id ?? null,
                            'term_id' => $term
                        ]);
                    } else {
                        $this->checkforduplicate($req->exam_id, $s->user_id, $req->subject_id, $term);
                    }
                } else {
                    $this->checkforduplicate($req->exam_id, $s->user_id, $req->subject_id, $term);
                }
            }
            return redirect()->route('marks.manage', [$req->exam_id, $req->department_id, $req->subject_id, $req->year]);
        }
    }

    public function selectordownload(MarkSelector $req)
    {
        $cur_year = Qs::getSetting('current_session');
        $planning = Qs::getSetting('planning_open');

            $user = Auth::user()->id;
            $data = $req->only(['exam_id', 'department_id', 'subject_id', 'year']);
            $like = $req->only(['department_id', 'subject_id']);
            $d2 = $req->only(['exam_id']);
            $d = $req->only(['department_id', 'subject_id']);
            $d['session'] = $data['year'] = $d2['year'] = $req->year;
            $like['session'] = $data['year'];
            $term = DB::table('exams')->where('id', $req->exam_id)->first();
            // dd($term->term);
            // $students = Enrolled::where('course_id',$req->course_id)->get();

            if (Qs::userIsTeacher()) {
                $students = DB::table('enrolleds')->where('year', $req->year)->where('course_id', $req->subject_id)->where('teacher_id', $user)->get();
            } else {
                $students = DB::table('enrolleds')
                    ->where('year', $req->year)
                    ->where('course_id', $req->subject_id)
                    ->where('term_id', $term->term)
                    ->get();
            }
            // dd($req->section_id);
            if ($students->count() < 1) {
                return back()->with('pop_warning', __('msg.nsec'));
            }

            foreach ($students as $s) {
                $data['student_id'] = $d2['student_id'] = $s->user_id;
                // $data['section_id'] = $s->sectin_id;

                $student = StudentRecord::find($s->user_id);
                $check = DB::table('marks')
                    ->where('year', $req->year)
                    ->where('exam_id', $req->exam_id)
                    ->where('subject_id', $req->subject_id)
                    ->where('student_id', $s->user_id)
                    ->first();

                $exam_check = DB::table('exam_records')
                    ->where('year', $req->year)
                    ->where('exam_id', $req->exam_id)
                    ->where('student_id', $s->user_id)
                    ->first();
                //    dd($check->subject_id);
                if ($check == null) {
                    Mark::create([
                        'student_id' => $s->user_id,
                        'year' => $req->year,
                        'exam_id' => $req->exam_id,
                        'subject_id' => $req->subject_id,
                        'my_class_id' => $student->my_class_id ?? null,
                        'section_id' => $student->section_id ?? null,
                        'department_id'=>$student->department_id ?? null,
                        'term_id' => $term->term
                    ]);

                    if ($exam_check == null) {
                        ExamRecord::create([
                            'student_id' => $s->user_id,
                            'exam_id' => $req->exam_id,
                            'my_class_id' => $student->my_class_id ?? null,
                            'section_id' => $student->section_id ?? null,
                            'department_id'=>$student->department_id ?? null,
                            'year' => $req->year
                        ]);
                    } else {
                    }
                } else {
                }
            }
            return redirect()->route('marks.download', [$req->exam_id, $req->department_id, $req->subject_id, $req->year]);
    }

    public function manage($exam_id, $department_id, $subject_id, $year)
    {
        $d['exam_id'] = $exam_id;
        $d['department_id'] = $department_id;
        $d['subject_id'] = $subject_id;
        $d['year'] = $year;


        $cur_year = Qs::getSetting('current_session');
        $planning = Qs::getSetting('planning_open');


        if ($planning == 1 && $year == $cur_year) {
            $d['years'] = DB::table('academic_year')->get();
            return view('pages.support_team.marks.no', $d);
        } else {
            $d = ['exam_id' => $exam_id, 'department_id' => $department_id, 'subject_id' => $subject_id, 'year' => $year];

            $d['marks'] = $check = Mark::where('exam_id', $exam_id)
                ->where('subject_id', $subject_id)
                ->where('year', $year)
                ->get();

            if ($d['marks']->count() < 1) {
                return $this->noStudentRecord();
            }
            $d['class_type'] = DB::table('class_types')->where('id', $department_id)->first();
            $d['m'] = Course::where('id', $subject_id)->first();
            $d['exams'] = $this->exam->all();
            $d['my_classes'] = $this->my_class->all();
            $d['sections'] = $this->my_class->getAllSections();
            $d['subjects'] = $this->my_class->getAllSubjects();
            $d['levels'] = $this->levels;


            // $d['year']= $year;
            $d['years'] = DB::table('academic_year')->get();
            $d['departments'] = ClassType::all();
            $d['selected'] = true;

            $d['grades'] = $grades = DB::table('grades')->orderBy('name', 'asc')->get();


            $gradeCounts = $check->groupBy('grade_id')->map(function ($group) {
                return $group->count();
            });

            // Prepare output with counts for each grade
            $output = [];
            foreach ($grades as $grade) {
                $output[$grade->id] = [
                    'grade' => $grade->name,
                    'count' => $gradeCounts->get($grade->id, 0), // Use 0 if no marks found for that grade
                ];
            }
            // Pass the output data to your view
            $d['count_out'] = $output;


            return view('pages.support_team.marks.manage', $d);
        }
    }




    public function download($exam_id, $department_id, $subject_id, $year)
    {
        $d['exam_id'] = $exam_id;
        $d['department_id'] = $department_id;
        $d['subject_id'] = $subject_id;
        $d['year'] = $year;


        $cur_year = Qs::getSetting('current_session');
        $planning = Qs::getSetting('planning_open');


        $d = ['exam_id' => $exam_id, 'department_id' => $department_id, 'subject_id' => $subject_id, 'year' => $year];

            $d['marks'] = $check = Mark::where('exam_id', $exam_id)
                ->where('subject_id', $subject_id)
                ->where('year', $year)
                ->get();

            if ($d['marks']->count() < 1) {
                return $this->noStudentRecord();
            }

            $d['m'] = Course::where('id', $subject_id)->first();
            $d['exams'] = $this->exam->all();
            $d['departments'] = ClassType::all();
            $d['sections'] = $this->my_class->getAllSections();
            $d['subjects'] = $this->my_class->getAllSubjects();
            $d['levels'] = $this->levels;


            // $d['year']= $year;
            $d['years'] = DB::table('academic_year')->get();
            $d['selected'] = true;
            $d['class_type'] = DB::table('class_types')->where('id', $department_id)->first();
            $d['grades'] = $grades = DB::table('grades')->orderBy('name', 'asc')->get();


            $gradeCounts = $check->groupBy('grade_id')->map(function ($group) {
                return $group->count();
            });

            // Prepare output with counts for each grade
            $output = [];
            foreach ($grades as $grade) {
                $output[$grade->id] = [
                    'grade' => $grade->name,
                    'count' => $gradeCounts->get($grade->id, 0), // Use 0 if no marks found for that grade
                ];
            }
            // Pass the output data to your view
            $d['count_out'] = $output;
            return view('pages.support_team.marks.managedownload', $d);        
    }

    public function update(Request $req, $exam_id, $department_id, $subject_id, $year)
    {
        $user = Auth::user()->id;
        // $students = Enrolled::where('subject_id',$req->subject_id)->where('teacher_id', $user)->get();
        $p = ['exam_id' => $exam_id, 'subject_id' => $subject_id, 'year' => $year];
        $d = $d3 = $all_st_ids = [];
        $exam = $this->exam->find($exam_id);
        $marks = $this->exam->getMark($p);
        $class_type = DB::table('class_types')->where('id', $department_id)->first();
        $course = Course::find($subject_id);
        $credit = Subject::where('id',$course->subject_id)->first()->credit;

        $mks = $req->all();

        /** Test, Exam, Grade **/
        foreach ($marks->sortBy('user.name') as $mk) {
            $student = StudentRecord::find($mk->student_id);

            
            $all_st_ids[] = $mk->student_id;
            $d['t1'] = $t1 = $mks['t1_' . $mk->id] ?? null;
            $d['t2'] = $t2 = $mks['t2_' . $mk->id] ?? null;
            $d['s1'] = $s1 = $mks['s1_' . $mk->id] ?? null;
            $d['tca'] = $tca = $t1 + $t2 + $s1 + $mk->tex3;
            $d['exm'] = $exm = $mks['exm_' . $mk->id] ?? null;
            $d['my_class_id'] = $student->my_class_id ?? null;
            $d['section_id'] = $student->section_id ?? null;
            $d['department_id'] = $req->department_id ?? null;

            /** SubTotal Grade, Remark, Cum, CumAvg**/

            // dd($tca);

            $d['tex' . $exam->term] = $total = $tca + $exm;

            if ($total > 100) {
                $d['tex' . $exam->term] = $total = 100;
            }
            /*   if($exam->term < 3){
                   $grade = $this->mark->getGrade($total, $class_type->id);
               }
               if($exam->term == 3){
                   $d['cum'] = $this->mark->getSubCumTotal($total, $st_id, $subject_id, $class_id, $this->year);
                   $d['cum_ave'] = $cav = $this->mark->getSubCumAvg($total, $st_id, $subject_id, $class_id, $this->year);
                   $grade = $this->mark->getGrade(round($cav), $class_type->id);
               }*/

            $ls = Grade::where('mark_from', '<=', $total)
                ->where('mark_to', '>=', $total)
                ->first();

            if ($ls != null) {
                $d['grade_id'] = $ls->id;
                $credit_and_grade = $ls->grade * $credit;
                $gt = round($credit_and_grade, 1);

                $gz = ($ls->grade >= 2.0) ? 0 : 1;

                


                DB::table('marks')->where('id', $mk->id)->update([
                    'grade_get' => $gt,
                    'failed' => $gz,
                ]);
            }

            $grade = DB::table('grades')->where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first();
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
        $tex = 'tex' . $exam->term;

        /*Sub Position End*/

        /* Exam Record Update */

        unset($p['subject_id']);

        foreach ($all_st_ids as $st_id) {

            $student = StudentRecord::find($st_id);
            $p['student_id'] = $st_id;

            $jk = DB::table('marks')
                ->where('student_id', $st_id)
                ->where('year', $year)
                ->where('exam_id', $exam_id)
                ->first();
            // dd($jk);

            $ct = DB::table('marks')
                ->where('student_id', $st_id)
                ->where('year', $year)
                ->where('exam_id', $exam_id)
                ->where($tex, '>', 0)
                ->sum('grade_get');



                 $credit_sum = Mark::join('courses', 'marks.subject_id', '=', 'courses.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->where('marks.exam_id', $exam_id)
                ->where('marks.student_id', $st_id)
                ->selectRaw('SUM(subjects.credit) as total_credit')
                ->first();



            $average = $ct / $credit_sum->total_credit;

            $avg = round($average, 3);

           $gz = ($jk->grade_get >= 2.0) ? 0 : 1;

            $exam_check = DB::table('exam_records')
                ->where('year', $year)
                ->where('exam_id', $exam_id)
                ->where('student_id', $st_id)
                ->first();

            if ($exam_check == null) {
                ExamRecord::create([
                    'student_id' => $st_id,
                    'exam_id' => $exam_id,
                    'my_class_id' => $student->my_class_id ?? null,
                    'section_id' => $student->section_id ?? null,
                    'department_id' => $student->department_id ?? null,
                    'year' => $req->year,
                    'total' => $ct,
                    'ave' => $avg,
                    'failed' => $gz
                ]);
            } else {

                if ($jk->grade_id == 10 || $jk->grade_id = null) {
                    ExamRecord::where('student_id', $st_id)
                        ->where('year', $year)
                        ->where('exam_id', $exam_id)
                        ->update([
                            'student_id' => $st_id,
                            'my_class_id' => $student->my_class_id ?? null,
                            'section_id' => $student->section_id ?? null,
                            'department_id' => $student->department_id ?? null,
                            'total' => $ct,
                            'ave' => $avg,
                            'failed' => $gz
                        ]);
                } else {

                    ExamRecord::where('student_id', $st_id)
                        ->where('year', $year)
                        ->where('exam_id', $exam_id)
                        ->update([
                            'my_class_id' => $student->my_class_id ?? null,
                            'section_id' => $student->section_id ?? null,
                            'department_id' => $student->department_id ?? null,
                            'student_id' => $st_id,
                            'total' => $ct,
                            'ave' => $avg,
                        ]);
                }
            }
        }

        /*Exam Record End*/

        return Qs::jsonUpdateOk();
    }

private function checkforduplicate($exam_id, $student_id, $subject_id, $term)
        {
            // Step 1: Check if student is enrolled in this subject
            $enrolled = DB::table('enrolleds')
                ->where('user_id', $student_id)
                ->where('course_id', $subject_id)
                ->where('year', $this->year)
                ->where('term_id', $term)
                ->exists(); // returns true/false

            // Step 2: Check if a mark exists for this subject
            $mark = DB::table('marks')
                ->where('exam_id', $exam_id)
                ->where('student_id', $student_id)
                ->where('subject_id', $subject_id)
                ->first();

            // Step 3: If mark exists but student is not enrolled, delete the invalid mark
            if ($mark && !$enrolled) {
                DB::table('marks')
                    ->where('id', $mark->id)
                    ->delete();

                return [
                    'status' => 'deleted',
                    'message' => "Mark for subject_id {$subject_id} removed — student not enrolled.",
                    'deleted_mark' => $mark,
                ];
            }

            // Step 4: Otherwise return info for valid cases
            if ($mark && $enrolled) {
                return [
                    'status' => 'valid',
                    'message' => "Mark for subject_id {$subject_id} is valid (enrolled).",
                    'mark' => $mark,
                ];
            }

            // Step 5: No mark found
            return [
                'status' => 'no_mark',
                'message' => "No mark found for subject_id {$subject_id}.",
            ];
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
        $tex = 'tex' . $exam->term;

        foreach ($marks as $mk) {

            $total = $mk->$tex;
            $d['grade_id'] = Grade::where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first();
            $grd = DB::table('grades')->where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first();

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
        foreach ($exrs as $exr) {

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
        if ($skill == 'AF' || $skill == 'PS') {
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

        if ($class_id && $section_id) {
            $d['sections'] = $this->my_class->getAllSections()->where('my_class_id', $class_id);
            $d['students'] = $st = $this->student->getRecordInschool(['my_class_id' => $class_id, 'section_id' => $section_id])->get()->sortBy('user.name');
            if ($st->count() < 1) {
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


    public function tabulation()
    {
        return view('pages.support_team.marks.passfailed.index');
    }

    public function print_tabulation($exam_id, $class_id, $section_id, $type)
    {
        // $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $this->year, 'failed'=>$type];

        // $sub_ids = $this->mark->getSubjectIDs($wh);
        // $st_ids = $this->mark->getStudentIDs($wh);
        // $et = Exam::find($exam_id);
        // $et_term = $et->term;
        $d['section'] = Section::find($section_id);
        $d['my_class'] = MyClass::find($class_id);
        $d['ex'] = Exam::find($exam_id);
        $d['year'] = $this->year;

        $d['students'] = $sub_ids = ExamRecord::join('student_records', 'student_records.user_id', '=', 'exam_records.student_id')
            ->where('student_records.my_class_id', $class_id)
            ->where('student_records.section_id', $section_id)
            ->where('exam_records.exam_id', $exam_id)
            ->where('exam_records.failed', $type)
            ->where('year', $this->year)
            ->get();


        if (count($sub_ids) < 1) {
            return Qs::goWithDanger('marks.tabulation', __('msg.srnf'));
        }

        // $d['subjects'] = Course::where('my_class_id',$class_id)->where('section_id', $section_id)->where('term_id',$et_term)->with('subject')->get()->sortBy('subject.name');
        // $d['students'] = $this->student->getRecordByUserIDs($st_ids)->get()->sortBy('user.name');

        // $d['my_class_id'] = $class_id;
        // $d['exam_id'] = $exam_id;
        // $d['year'] = $this->year;
        // $wh = ['exam_id' => $exam_id, 'my_class_id' => $class_id];
        // $d['marks'] = $mks = Mark::all();
        // $d['exr'] = $exr = $this->exam->getRecord($wh);

        // $d['my_class'] = $mc = $this->my_class->find($class_id);
        // $d['section']  = $this->my_class->findSection($section_id);
        // $d['ex'] = $exam = $this->exam->find($exam_id);
        // $d['tex'] = 'tex'.$exam->term;
        // $d['s'] = Setting::all()->flatMap(function($s){
        //     return [$s->type => $s->description];
        // });
        //$d['class_type'] = $this->my_class->findTypeByClass($mc->id);
        //$d['ct'] = $ct = $d['class_type']->code;

        return view('pages.support_team.marks.tabulation.print', $d);
    }

    public function tabulation_select(Request $req)
    {
        return redirect()->route('marks.tabulation', [$req->exam_id, $req->my_class_id, $req->section_id, $req->type]);
    }

    protected function verifyStudentExamYear($student_id, $year = null)
    {
        $years = $this->exam->getExamYears($student_id);
        $student_exists = $this->student->exists($student_id);

        if (!$year) {
            if ($student_exists && $years->count() > 0) {
                $d = ['years' => $years, 'student_id' => $student_id];

                return view('pages.support_team.marks.select_year', $d);
            }

            return $this->noStudentRecord();
        }

        return ($student_exists && $years->contains('year', $year)) ? true : false;
    }

    protected function noStudentRecord()
    {
        return redirect()->route('marks.index')->with('flash_danger', __('msg.srnf'));
    }

    protected function checkPinVerified($st_id)
    {
        return Session::has('pin_verified') && Session::get('pin_verified') == $st_id;
    }


    public function destroy($id)
    {


        $all = DB::table('marks')->find($id);
        $student_id = $all->student_id;
        $year = $all->year;
        $subject = $all->subject_id;
        $exam_id = $all->exam_id;
        $exam = DB::table('exams')->find($exam_id);
        $tex = 'tex' . $exam->term;

        //deleting grade
        DB::table('enrolleds')->where('user_id', $student_id)->where('course_id', $subject)->where('year', $year)->delete();

        //deleting mark
        DB::table('marks')->where('id', $id)->delete();

        $ct = DB::table('marks')
                ->where('student_id', $student_id)
                ->where('year', $year)
                ->where('exam_id', $exam_id)
                ->where($tex, '>', 0)
                ->sum('grade_get');



                 $credit_sum = Mark::join('courses', 'marks.subject_id', '=', 'courses.id')
                ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                ->where('marks.exam_id', $exam_id)
                ->where('marks.student_id', $student_id)
                ->selectRaw('SUM(subjects.credit) as total_credit')
                ->first();



            $average = $ct / $credit_sum->total_credit;

        DB::table('exam_records')->where('student_id', $student_id)->where('year', $year)->where('exam_id', $exam_id)->update([
            'total' => $ct,
            'ave' => $average
        ]);

        return redirect()->route('marks.show', [Qs::hash($student_id), $year]);
    }

    public function pass()
    {
        return view('pages.support_team.marks.passfailed.index');
    }


    public function clinical_index()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['years'] = DB::table('academic_year')->get();
        $d['selected'] = false;
        $d['planning'] = Qs::getSetting('Close_Grade_Entry');



        return view('pages.support_team.marks.clinicalindex', $d);
    }


    public function clinical(Request $req)
    {
        // Extract request data
        $examId = $req->exam_id;
        $sectionId = $req->section_id;
        $myClassId = $req->my_class_id;
        $subjectId = $req->subject_id;
        $year = $req->year;

        // dd($req);
        // Fetch academic years
        $years = DB::table('academic_year')->get();

        // Fetch course details
        $course = Course::find($subjectId);

        // Fetch class type
        $classType = $this->my_class->findTypeByClass($myClassId);

        // Fetch term details
        $term = DB::table('exams')->where('id', $examId)->first();

        // Fetch enrolled students for the given year, subject, and term
        $students = DB::table('enrolleds')
            ->where('year', $year)
            ->where('course_id', $subjectId)
            ->where('term_id', $term->term)
            ->get();

        // Fetch marks for the given exam, subject, and year
        $marks = Mark::where('exam_id', $examId)
            ->where('subject_id', $subjectId)
            ->where('year', $year)
            ->get();

        // Check if there are no students enrolled
        if ($students->isEmpty()) {
            return view('pages.support_team.marks.edit_clinical', [
                'exam_id' => $examId,
                'section_id' => $sectionId,
                'my_class_id' => $myClassId,
                'subject_id' => $subjectId,
                'year' => $year,
                'years' => $years,
                'm' => $course,
                'class_type' => $classType,
                'marks' => $marks,
                'message' => 'No students enrolled for the selected criteria.',
            ]);
        }

        // Process each student
        foreach ($students as $student) {
            $studentId = $student->user_id;

            // Check if marks already exist for the student
            $existingMark = Mark::where('year', $year)
                ->where('exam_id', $examId)
                ->where('subject_id', $subjectId)
                ->where('student_id', $studentId)
                ->first();

            // If marks do not exist, create new records
            if (!$existingMark) {
                $studentRecord = StudentRecord::find($studentId);

                // Create a new mark record
                Mark::create([
                    'student_id' => $studentId,
                    'year' => $year,
                    'exam_id' => $examId,
                    'subject_id' => $subjectId,
                    'my_class_id' => $studentRecord->my_class_id,
                    'section_id' => $studentRecord->section_id,
                    'term_id' => $term->term,
                ]);

                // Create a new exam record
                ExamRecord::create([
                    'student_id' => $studentId,
                    'exam_id' => $examId,
                    'my_class_id' => $studentRecord->my_class_id,
                    'section_id' => $studentRecord->section_id,
                    'year' => $year,
                ]);
            }
        }

        // Prepare data for the view
        $data = [
            'exam_id' => $examId,
            'section_id' => $sectionId,
            'my_class_id' => $myClassId,
            'subject_id' => $subjectId,
            'year' => $year,
            'years' => $years,
            'm' => $course,
            'class_type' => $classType,
            'marks' => $marks,
        ];

        // Return the view with data
        return view('pages.support_team.marks.edit_clinical', $data);
    }

    public function update_clinical(Request $req, $exam_id, $class_id, $section_id, $subject_id, $year)
    {
        $p = ['exam_id' => $exam_id, 'subject_id' => $subject_id, 'year' => $year];

        $marks = $this->exam->getMark($p);
        $exam = $this->exam->find($exam_id);

        $mks = $req->all();
        // dd($mks);
        /** Test, Exam, Grade **/
        foreach ($marks->sortBy('user.name') as $mk) {
            $student = StudentRecord::find($mk->student_id);

            $all_st_ids[] = $mk->student_id;
            $tex3 = $mks['tex3_' . $mk->id];

            $tc = $mk->t2 + $mk->t1 + $mk->s1 + $tex3 + $mk->exm;
            $d['tex' . $exam->term] = $total = $tc;
            // dd($tc);

            Mark::find($mk->id)->update([
                'tex3' => $tex3,
            ]);

            if ($total > 100) {
                $d['tex' . $exam->term] = $total = 100;
            }
            $this->exam->updateMark($mk->id, $d);


            $ls = Grade::where('mark_from', '<=', $total)
                ->where('mark_to', '>=', $total)
                ->first();

            if ($ls != null) {
                $d['grade_id'] = $ls->id;
                $gt = round($ls->grade, 1);
                $lg = $ls->id ?? NULL;

                DB::table('marks')->where('id', $mk->id)->update([
                    'grade_get' => $gt,
                    'grade_id' => $lg
                ]);
            }

            // $grade = DB::table('grades')->where('mark_from', '<=', $total)->where('mark_to', '>=', $total)->first();
            // $d['grade_id'] = $grade ? $grade->id : NULL;
            // $this->exam->updateMark($mk->id, $d);
        }


        $exam = DB::table('exams')->find($exam_id);
        $tex = 'tex' . $exam->term;

        /*Sub Position End*/

        /* Exam Record Update */

        unset($p['subject_id']);

        foreach ($all_st_ids as $st_id) {

            $student = StudentRecord::find($st_id);
            $p['student_id'] = $st_id;

            $jk = DB::table('marks')
                ->where('student_id', $st_id)
                ->where('year', $year)
                ->where('exam_id', $exam_id)
                ->first();
            // dd($jk);

            $ct = DB::table('marks')
                ->where('student_id', $st_id)
                ->where('year', $year)
                ->where('exam_id', $exam_id)
                ->where($tex, '>', 0)
                ->sum('grade_get');

            $a = DB::table('marks')
                ->where('student_id', $st_id)
                ->where('year', $year)
                ->where('exam_id', $exam_id)
                ->where($tex, '>', 0)
                ->select($tex)
                ->avg('grade_get');


            $avg = round($a, 3);

            // dd($jk->grade_get);
            $gz = 0;
            $go = 1;

            if ($jk->grade_id == 10 || $jk->grade_id = null) {
                ExamRecord::where('student_id', $st_id)
                    ->where('year', $year)
                    ->where('exam_id', $exam_id)
                    ->update([
                        'student_id' => $st_id,
                        'my_class_id' => $student->my_class_id ?? $class_id,
                        'section_id' => $student->section_id ?? $section_id,
                        'total' => $ct,
                        'ave' => $avg,
                        'failed' => $go
                    ]);
            } else {

                ExamRecord::where('student_id', $st_id)
                    ->where('year', $year)
                    ->where('exam_id', $exam_id)
                    ->update([
                        'my_class_id' => $student->my_class_id ?? $class_id,
                        'section_id' => $student->section_id ?? $section_id,
                        'student_id' => $st_id,
                        'total' => $ct,
                        'ave' => $avg,
                    ]);
            }
        }
        return Qs::jsonUpdateOk();
    }






    // public function clinical_get($exam_id, $section_id, $my_class_id, $subject_id, $year)
    // {
    //     // Extract request data
    //     $examId = $exam_id;
    //     $sectionId = $section_id;
    //     $myClassId = $my_class_id;
    //     $subjectId = $subject_id;
    //     $year = $year;

    //     // dd($my_class_id);
    //     // Fetch academic years
    //     $years = DB::table('academic_year')->get();

    //     // Fetch course details
    //     $course = Course::find($subjectId);

    //     // Fetch class type
    //     $classType = $this->my_class->findTypeByClass($myClassId);

    //     // Fetch term details
    //     $term = DB::table('exams')->where('id', $examId)->first();

    //     // Fetch enrolled students for the given year, subject, and term
    //     $students = DB::table('enrolleds')
    //         ->where('year', $year)
    //         ->where('course_id', $subjectId)
    //         ->where('term_id', $term->term)
    //         ->get();

    //     // Fetch marks for the given exam, subject, and year
    //     $marks = Mark::where('exam_id', $examId)
    //         ->where('subject_id', $subjectId)
    //         ->where('year', $year)
    //         ->get();

    //     // Check if there are no students enrolled
    //     if ($students->isEmpty()) {
    //         return view('pages.support_team.marks.edit_clinical', [
    //             'exam_id' => $examId,
    //             'section_id' => $sectionId,
    //             'my_class_id' => $myClassId,
    //             'subject_id' => $subjectId,
    //             'year' => $year,
    //             'years' => $years,
    //             'm' => $course,
    //             'class_type' => $classType,
    //             'marks' => $marks,
    //             'message' => 'No students enrolled for the selected criteria.',
    //         ]);
    //     }

    //     // Process each student
    //     foreach ($students as $student) {
    //         $studentId = $student->user_id;

    //         // Check if marks already exist for the student
    //         $existingMark = Mark::where('year', $year)
    //             ->where('exam_id', $examId)
    //             ->where('subject_id', $subjectId)
    //             ->where('student_id', $studentId)
    //             ->first();



    //         // If marks do not exist, create new records
    //         if (!$existingMark) {
    //             $studentRecord = StudentRecord::find($studentId);

    //             // Create a new mark record
    //             Mark::create([
    //                 'student_id' => $studentId,
    //                 'year' => $year,
    //                 'exam_id' => $examId,
    //                 'subject_id' => $subjectId,
    //                 'my_class_id' => $studentRecord->my_class_id ?? $my_class_id,
    //                 'section_id' => $studentRecord->section_id ?? $section_id,
    //                 'term_id' => $term->term,
    //             ]);

    //             // Create a new exam record
    //             ExamRecord::create([
    //                 'student_id' => $studentId,
    //                 'exam_id' => $examId,
    //                 'my_class_id' => $studentRecord->my_class_id ?? $my_class_id,
    //                 'section_id' => $studentRecord->section_id ?? $section_id,
    //                 'year' => $year,
    //             ]);
    //         }
    //     }

    //     // Prepare data for the view
    //     $data = [
    //         'exam_id' => $examId,
    //         'section_id' => $sectionId,
    //         'my_class_id' => $myClassId,
    //         'subject_id' => $subjectId,
    //         'year' => $year,
    //         'years' => $years,
    //         'm' => $course,
    //         'class_type' => $classType,
    //         'marks' => $marks,
    //     ];

    //     // Return the view with data
    //     return view('pages.support_team.marks.edit_clinical', $data);
    // }

    private function student_fees($student, $year, $exam)
{
        $term = Exam::find($exam)?->term;

        $records = PaymentRecord::with('payment')
            ->where('student_id', $student)
            ->where('year', $year)
            ->where('term_id', $term)
            ->get();

        $notFullyPaid = $records->filter(function ($record) {
                if (!$record->payment) return false;

                $expected = $record->payment->amount ?? 0;
                return $record->amt_paid < $expected;
            });

        // Split into LRD and USD and check balances
        $amount_paid_lrd = $records->filter(function ($record) {
            return $record->payment && $record->payment->description === 'LRD';
        })->sum('amt_paid');

        $amount_paid_usd = $records->filter(function ($record) {
            return $record->payment && $record->payment->description === 'USD';
        })->sum('amt_paid');


        return [
            'status' => $notFullyPaid->isNotEmpty() ? 1 : 0,
            'usd_paid' => $amount_paid_usd,
            'lrd_paid' => $amount_paid_lrd,
        ];
}

}
