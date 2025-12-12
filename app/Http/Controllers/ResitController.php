<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mark\MarkSelector;
use App\Models\ClassType;
use App\Models\Course;
use App\Models\Enrolled;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\Subject;
use App\Repositories\ExamRepo;
use App\Repositories\MarkRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class ResitController extends Controller
{
    //
    protected $my_class, $exam, $student, $year, $user, $mark, $levels = ['Freshmen', 'Sophomore', 'Junior', 'Senior'];

    public function __construct(MyClassRepo $my_class, ExamRepo $exam, StudentRepo $student, MarkRepo $mark)
    {
        $this->exam = $exam;
        $this->mark = $mark;
        $this->student = $student;
        $this->my_class = $my_class;
        $this->year = Qs::getSetting('current_session');

        // $this->middleware('teamSAT', ['except' => ['show', 'year_selected', 'year_selector', 'print_view'] ]);
    }
    protected function noStudentRecord()
    {
        return redirect()->route('resits')->with('flash_danger', __('msg.srnf'));
    }


    public function selector(MarkSelector $req)
    {

        // dd($req->all());
        $sr = DB::table('sections')->where('id', $req->section_id)->first();
        if (Qs::getSetting('close_grade_entry') == 1 && Qs::userIsTeacher()) {
            $d['years'] = DB::table('academic_year')->get();
            return view('pages.support_team.marks.no',$d);
        } else {

            $user = Auth::user()->id;
            $data = $req->only(['exam_id', 'department_id', 'subject_id', 'year']);
            $like = $req->only(['department_id', 'subject_id']);
            $d2 = $req->only(['exam_id']);
            $d = $req->only(['department_id', 'subject_id']);
            $d['session'] = $data['year'] = $d2['year'] = $req->year;
            $like['session'] = $data['year'];
            $term = DB::table('exams')->where('id', $req->exam_id)->first();

            // dd($like);
            // $students = Enrolled::where('course_id',$req->course_id)->get();

            if (Qs::userIsTeacher()) {
                $students = Mark::where('subject_id', $req->subject_id)
                    // ->where('teacher_id', $user)
                    ->where('failed', 1)
                    // ->where('term_id', $term->term)
                    ->where('exam_id', $req->exam_id)
                    ->where('year', $req->year)
                    ->get();
            } else {
                $students = Mark::where('subject_id', $req->subject_id)
                    ->where('failed', 1)
                    // ->where('term_id', $term->term)
                    ->where('year', $req->year)
                    ->where('exam_id', $req->exam_id)
                    ->get();
            }
            // dd($req->section_id);
            if ($students->count() < 1) {
                return back()->with('pop_error', __('msg.nsec'));
            }

            return redirect()->route('resits.manage',[$req->exam_id, $req->department_id, $req->subject_id, $req->year]);
        }
    }


    public function manage($exam_id, $department_id, $subject_id, $year)
    {
        // dd($exam_id, $department_id, $subject_id, $year);

        $term = DB::table('exams')->where('id', $exam_id)->first();
        if (Qs::getSetting('close_grade_entry') == 1 && Qs::userIsTeacher()) {
            $d['years'] = DB::table('academic_year')->get();
            return view('pages.support_team.marks.no',$d);
        } else {
            $d = ['exam_id' => $exam_id, 'department_id' => $department_id, 'subject_id' => $subject_id, 'year' => $year];


            $d['marks'] = Mark::where('exam_id', $exam_id)
                ->where('subject_id', $subject_id)
                ->where('year', $year)
                ->where('failed', 1)
                // ->where('term_id', $term->term)
                ->get();

            if ($d['marks']->count() < 1) {
                return $this->noStudentRecord();
            }
            $d['class_type'] = DB::table('class_types')->where('id', $department_id)->first();
            $d['years'] = DB::table('academic_year')->get();
            $d['m'] = Course::where('id', $subject_id)->first();
            $d['exams'] = $this->exam->all();
            $d['my_classes'] = $this->my_class->all();
            $d['sections'] = $this->my_class->getAllSections();
            $d['subjects'] = $this->my_class->getAllSubjects();
            $d['levels'] = $this->levels;
            $d['departments'] = ClassType::all();
            $d['selected'] = true;
            if (Qs::userIsTeacher()) {
                $d['subjects'] = Enrolled::where('teacher_id', Auth::user()->id)
                    ->where('year', $year)
                    // ->where('department_id', $department_id)
                    ->where('subject_id', $subject_id)
                    ->get();
            }

            return view('pages.support_team.marks.resit', $d);
        }
    }

    public function update(Request $req, $exam_id, $department_id, $subject_id, $year)
    {
        $user = Auth::user()->id;
        // $students = Enrolled::where('subject_id',$req->subject_id)->where('teacher_id', $user)->get();

        $p = ['exam_id' => $exam_id, 'subject_id' => $subject_id, 'year' => $year,];

        $d = $all_st_ids = [];

        $exam = $this->exam->find($exam_id);
        $marks = Mark::where('exam_id', $exam_id)
            ->where('subject_id', $subject_id)
            ->where('year', $year)
            ->where('failed', '=', 1)
            ->get();
        $class_type = $this->my_class->findTypeByClass($department_id);
        $course = Course::find($subject_id);
        $credit = Subject::where('id',$course->subject_id)->first()->credit;

        $mks = $req->all();

        /** Test, Exam, Grade **/
        foreach ($marks->sortBy('user.name') as $mk) {
            $all_st_ids[] = $mk->student_id;
            if ($mk != null) {
                $d['t1'] = $t1 = 0;
                $d['t2'] = $t2 = 0;
                $d['s1'] = $s1 = 0;
                $d['tex3'] = $tex3 = 0;
                $d['tca'] = $tca = $t1 + $t2 + $s1;
                $d['exm'] = $exm = $mks['exm_' . $mk->id];
            }
            /** SubTotal Grade, Remark, Cum, CumAvg**/

            $d['tex' . $exam->term] = $total = $tca + $exm;

            if ($total > 100) {
                $d['tex' . $exam->term] = $d['t1'] = $d['t2'] = $d['t3'] = $d['s1'] = $d['t4'] = $d['tca'] = $d['exm'] = NULL;
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
                $rg = 0;
                $credit_and_grade = $ls->grade * $credit;
                $gt = round($credit_and_grade, 1);
                if ($ls->grade == 0 || $ls->grade == null) {
                    $rg = 1;
                } else {

                }
                // dd($gt);

                DB::table('marks')->where('id', $mk->id)->update([
                    'grade_get' => $gt,
                    'failed' => $rg
                ]);

                DB::table('exam_records')->where('exam_id', $exam_id)->where('year', $year)->where('student_id', $mk->student_id)->update([
                    'failed' => $rg
                ]);
            }
            $grade = DB::table('grades')
                ->where('mark_from', '<=', $total)
                ->where('mark_to', '>=', $total)
                ->first();
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

            $p['student_id'] = $st_id;
            // $d3['total'] = $this->mark->getExamTotalTerm($exam, $st_id,  $this->year);
            // $d3['ave'] = $this->mark->getExamAvgTerm($exam, $st_id, $this->year);
            // $d3['class_ave'] = $this->mark->getClassAvg($exam, $this->year);
            // $d3['pos'] = $this->mark->getPos($st_id, $exam,  $this->year);

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

            $gz = ($ct >= 2.0) ? 0 : 1;



            // $avg = round($a, 3);

            DB::table('exam_records')
                ->where('student_id', $st_id)
                ->where('year', $year)
                ->where('exam_id', $exam_id)
                ->update([
                    'total' => $ct,
                    'ave' => $avg,
                    'resit' => 1,
                    'failed' => $gz
                ]);


        }
        /*Exam Record End*/

        return Qs::jsonUpdateOk();
    }

}
