<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\Student;
use App\Models\ClassType;
use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Payment;
use App\Models\PaymentRecord;
use App\Models\Section;
use App\Models\Setting;
use App\Models\StudentRecord;
use App\Repositories\ExamRepo;
use App\Repositories\MarkRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
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
    }

    public function index()
    {
        $year = Qs::getSetting('current_session');
        $total_payment = DB::table('payment_records')->sum('amt_paid');
        $total_expected = DB::table('payments')->sum('amount');

        DB::table('accounting')->Insert([
            'session' => $year,
            'total_collect' => $total_payment,
            'amount_excepted' => $total_expected,

        ]);
    }
 

    public function index_student_records()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['departments'] = ClassType::all();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['years'] = DB::table('academic_year')->get();
        $d['selected'] = false;
        $d['levels'] = $this->levels;
        return view('pages.support_team.accounting.index', $d);

    }


    public function student_fees(Request $request)
    {
        $year = $request->year;
        $exam = $request->exam_id;
        $department = $request->department_id;
        $class_id = $request->my_class_id;
        $section_id = $request->section_id;
        $filterStatus = $request->balance_inquiry; // should be 'clear', 'unclear', or null
        $exam = $request->exam_id;
        $semester = Exam::find($exam)->term;

        if($class_id == null)
        {
            $dept = ClassType::find($department)->name;
            $class_name = null;
            $section_name = null;
        }
        else{
            $class_name = MyClass::find($class_id)->name;
            $section_name = Section::find($section_id)->name;
        }
    
        // Get students
        $students = StudentRecord::where('status', 1)
            ->when($class_id && $section_id, function ($query) use ($class_id, $section_id) {
                $query->where('my_class_id', $class_id);
            }, function ($query) use ($department) {
                $query->where('department_id', $department);
            })->with('user') // assumes StudentRecord has a `user()` relationship to student profile
            ->get();
 
        $report = [];
    
        foreach ($students as $record) {
            $studentId = $record->user_id;
   
            // Total expected amount (from department payments)
            $expectedUsd = Payment::when($class_id && $section_id, function ($query) use ($class_id, $section_id) {
                $query->WhereRaw("FIND_IN_SET(?, my_class_id)", [$class_id])
                ->WhereRaw("FIND_IN_SET(?, section_id)", [$section_id]);
            }, function ($query) use ($department) {
                $query->where('department_id', $department);
            })
            ->where('year', $year)
            ->where('term_id', $semester)
            ->where('description', 'USD')
            ->sum('amount');
        
    
            $expectedLrd = Payment::when($class_id && $section_id, function ($query) use ($class_id, $section_id) {
                $query->whereRaw("FIND_IN_SET(?, my_class_id)", [$class_id])
                ->WhereRaw("FIND_IN_SET(?, section_id)", [$section_id]);
            }, function ($query) use ($department) {
                $query->where('department_id', $department);
            })
            ->where('year', $year)
            ->where('term_id', $semester)
            ->where('description', 'LRD')
            ->sum('amount');
    
            // Total paid by student
            $paidUsd = PaymentRecord::where('student_id', $studentId)
                                    ->where('year', $year)
                                    ->where('term_id', $semester)
                                    ->whereHas('payment', function ($q) {
                                        $q->where('description', 'USD');
                                    })->sum('amt_paid');
    
            $paidLrd = PaymentRecord::where('student_id', $studentId)
                                    ->where('year', $year)
                                    ->where('term_id', $semester)
                                    ->whereHas('payment', function ($q) {
                                        $q->where('description', 'LRD');
                                    })->sum('amt_paid');
  
            // Balance
            $balanceUsd = $expectedUsd - $paidUsd;
            $balanceLrd = $expectedLrd - $paidLrd;

            // Determine fee status
            $studentStatus = ($balanceUsd <= 0 && $balanceLrd <= 0) ? 'clear' : 'unclear';
   
            // Filter by requested status if specified
            if ($filterStatus && $studentStatus !== $filterStatus) {
                continue;
            }
    
            // Build report
            $report[] = [
                'student_id' => $studentId,
                'name' => $record->user->name ?? 'Unknown',
                'expected_usd' => $expectedUsd,
                'expected_lrd' => $expectedLrd,
                'paid_usd' => $paidUsd,
                'paid_lrd' => $paidLrd,
                'balance_usd' => $balanceUsd,
                'balance_lrd' => $balanceLrd,
                'status' => $studentStatus
            ];
        }
        $exams = $this->exam->getExam(['year' => $this->year]);
        $departments = ClassType::all();
        $subjects = $this->my_class->getAllSubjects();
        $years = DB::table('academic_year')->get();
        $selected = false;
        $levels = $this->levels;

        
        
    
        return view('pages.support_team.accounting.result', compact('class_id','section_name','class_name','report','year','semester','filterStatus','exams','departments','subjects','years','selected','levels'));
    }    

}
