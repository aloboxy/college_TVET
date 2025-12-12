<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ExamRecord;

class PromotionController extends Controller
{
    protected $my_class, $student;
    public $year;
    public $pass;

    public $tc;
    public $ts;
    public $fc;
    public $fs;

    public function __construct(MyClassRepo $my_class, StudentRepo $student)
    {
        $this->middleware('teamSA');
        $this->my_class = $my_class;
        $this->student = $student;
    }

   public function promotion($fc = NULL, $fs = NULL, $tc = NULL, $ts = NULL, $year = NULL, $exam_id = NULL, $pass = NULL)
{
    // Get latest academic year and generate the new year string
    $latest_year_record = DB::table('academic_year')->latest('id')->first();
    $old_year_str = $latest_year_record ? $latest_year_record->year : '2023-2024'; // fallback if null
    $old_yr_parts = explode('-', $old_year_str);
    $new_start = isset($old_yr_parts[0]) ? (int)$old_yr_parts[0] + 1 : date('Y') + 1;
    $new_end = isset($old_yr_parts[1]) ? (int)$old_yr_parts[1] + 1 : date('Y') + 2;
    $d['old_year'] = $old_year_str;
    $d['new_year'] = "{$new_start}-{$new_end}";

    // Load class and section data
    $d['my_classes'] = $this->my_class->all();
    $d['sections'] = $this->my_class->getAllSections();
    $d['selected'] = false;
    $d['years'] = DB::table('academic_year')->get(); // just the year strings

    if ($fc && $fs && $tc && $ts) {
        $d['selected'] = true;

        // Normalize inputs
        $fc = intval($fc);
        $fs = intval($fs);
        $tc = intval($tc);
        $ts = intval($ts);

        $d['fc'] = $fc;
        $d['fs'] = $fs;
        $d['tc'] = $tc;
        $d['ts'] = $ts;
        $d['pass'] = $pass;
        $d['exam_id'] = $exam_id;
        $d['year'] = $year;
        $d['exam'] = DB::table('exams')->where('id', $exam_id)->first();


        // Shared base query for students
        $studentsQuery = ExamRecord::join('student_records', 'student_records.user_id', '=', 'exam_records.student_id')
            ->where('exam_records.exam_id', $exam_id)
            ->where('exam_records.year', $year)
            ->whereExists(function ($query) use ($fc, $fs, $year) {
                $query->select(DB::raw(1))
                    ->from('student_records')
                    ->whereColumn('student_records.user_id', 'exam_records.student_id')
                    ->where('student_records.my_class_id', $fc)
                    ->where('student_records.section_id', $fs);
                    // ->where('student_records.session', $year);
            })
            ->whereNotExists(function ($query) use ($fc, $fs, $year) {
                $query->select(DB::raw(1))
                    ->from('promotions')
                    ->whereColumn('promotions.student_id', 'student_records.user_id')
                    ->where('promotions.from_class', $fc)
                    ->where('promotions.from_section', $fs)
                    ->where('promotions.from_session', $year);
            });

        // Add pass/fail filter
        $students = $pass == 0
            ? $studentsQuery->where('exam_records.failed', 0)->get() // Passed
            : $studentsQuery->where('exam_records.failed', 1)->get(); // Failed

        $d['students'] = $students;
    }


    return view('pages.support_team.students.promotion.index', $d);
}


    public function selector(Request $req)
    {


        return redirect()->route('students.promotion', [$req->fc, $req->fs, $req->tc, $req->ts, $req->year, $req->exam_id, $req->pass]);
    }

    public function promote(Request $req, $fc, $fs, $tc, $ts, $year, $exam_id, $pass)
    {

        $d['exam'] = $exam = DB::table('exams')->where('id', $exam_id)->first();

        if ($exam->name == 'Semester II') {
            $oy = $year;
            $d = [];
            $old_yr = explode('-', $oy);
            $ny = ++$old_yr[0] . '-' . ++$old_yr[1];
            // $students = $this->student->getRecord(['my_class_id' => $fc, 'section_id' => $fs, 'session' => $oy])->get()->sortBy('user.name');
        } else {
            $ny = $year;
            $oy = $year;
        }

        $d['students'] = $students = ExamRecord::join('student_records', 'student_records.user_id', '=', 'exam_records.student_id')
            ->where('student_records.my_class_id', $fc)
            ->where('student_records.section_id', $fs)
            ->where('exam_records.exam_id', $exam_id)
            ->where('exam_records.failed', $pass)
            ->where('exam_records.year', $year)
            ->get();

        // dd($students, $pass);

        if ($students->count() < 1) {
            return redirect()->route('students.promotion')->with('flash_danger', __('msg.srnf'));
        }

        foreach ($students as $st) {
            $p = $pass;
            if ($p == 0) { // Promote
                $d['my_class_id'] = $tc;
                $d['section_id'] = $ts;
                $d['session'] = $ny;
                $d['semester'] = $exam_id;
            }
            if ($p == 1) { // Don't Promote
                $d['my_class_id'] = $tc;
                $d['section_id'] = $ts;
                $d['session'] = $ny;
            }
            if ($p === 'G') { // Graduated
                $d['my_class_id'] = $fc;
                $d['section_id'] = $fs;
                $d['grad'] = 1;
                $d['grad_date'] = $oy;
            }
            if ($p == 0) {
                $r = 'P';
            } elseif ($p == 1) {
                $r = 'D';
            } else {
                $r = 'G';
            }

            $this->student->updateRecord($st->id, $d);


            //     Insert New Promotion Data
            $promote['from_class'] = $fc;
            $promote['from_section'] = $fs;
            $promote['grad'] = ($r === 'G') ? 1 : 0;
            $promote['to_class'] = in_array($p, ['D', 'G']) ? $fc : $tc;
            $promote['to_section'] = in_array($p, ['D', 'G']) ? $fs : $ts;
            $promote['student_id'] = $st->user_id;
            $promote['from_session'] = $oy;
            $promote['to_session'] = $ny;
            $promote['status'] = $r;

            $this->student->createPromotion($promote);
        }
        return redirect()->route('students.promotion')->with('flash_success', __('msg.update_ok'));
    }

    public function manage()
    {
        $data['promotions'] = $this->student->getAllPromotions();
        $data['old_year'] = Qs::getCurrentSession();
        $data['new_year'] = Qs::getCurrentSession();

        return view('pages.support_team.students.promotion.reset', $data);
    }

    public function reset($promotion_id)
    {
        $this->reset_single($promotion_id);

        return redirect()->route('students.promotion_manage')->with('flash_success', __('msg.update_ok'));
    }

    public function reset_all()
    {
        $next_session = Qs::getNextSession();
        $where = ['from_session' => Qs::getCurrentSession(), 'to_session' => Qs::getCurrentSession()];
        $proms = $this->student->getPromotions($where);



        if ($proms->count()) {
            foreach ($proms as $prom) {
                $this->reset_single($prom->id);

                // Delete Marks if Already Inserted for New Session
                // $this->delete_old_marks($prom->student_id, $next_session);
            }
        }

        return Qs::jsonUpdateOk();
    }

    protected function delete_old_marks($student_id, $year)
    {
        Mark::where(['student_id' => $student_id, 'year' => $year])->delete();
    }

    protected function reset_single($promotion_id)
    {
        $prom = $this->student->findPromotion($promotion_id);

        $data['my_class_id'] = $prom->from_class;
        $data['section_id'] = $prom->from_section;
        $data['session'] = $prom->from_session;
        $data['grad'] = 0;
        $data['grad_date'] = null;

        $this->student->update(['user_id' => $prom->student_id], $data);

        // return $this->student->deletePromotion($promotion_id);r
    }
}
