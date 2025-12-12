<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Helpers\Qs; 

class BulkTranferStudent extends Controller
{
    public $college;
    public $department;
    public $major;
    public $minor;
    public $cohort;
    public $class;
    public $level;
    public function __construct()
    {
        $this->college = null;
        $this->department =null;
        $this->major = null;
        $this->minor = null;
        $this->cohort = null;
        $this->class = null;
        $this->level = null;
    }
    public function selectedCollege()
    {
        $colleges = DB::table('colleges')->get();
        session()->forget('transfer');
        return view('pages.support_team.bulk_transfer_student.select', compact('colleges'));
    }

    public function middleman(Request $request)
    {
        $transfer = session([
            'transfer.college'    => $request->college_id,
            'transfer.department' => $request->department_id,
            'transfer.major'      => $request->major,
            'transfer.minor'      => $request->minor,
            'transfer.cohort'     => $request->section_id,
            'transfer.class'      => $request->my_class_id,
            'transfer.level'      => $request->level,
        ]);

        return redirect()->route('bulk.transfer.students');
    }

    //
    public function index()
    {
        return view('pages.support_team.bulk_transfer_student.index');
    }

    public function store(Request $request)
    {
        $transferData = session('transfer');
        $selectedIds = $request->ids;
    
        if (!$selectedIds || !$transferData) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing selected students or transfer data.'
            ], 400);
        }
    
        foreach ($selectedIds as $studentId) {
            DB::table('student_records')
                ->where('id', $studentId)
                ->update([
                    'college_id'     => $transferData['college'],
                    'department_id'  => $transferData['department'],
                    'major'          => $transferData['major'],
                    'minor'          => $transferData['minor'],
                    'section_id'     => $transferData['cohort'],
                    'my_class_id'    => $transferData['class'],
                    'level'          => $transferData['level'],
                ]);
        }
    
        return response()->json([
            'message' => 'Bulk transfer completed successfully.',
        ]);
    }
    
    
      public function list(Request $request)
    {
        $student = DB::table('users')
            ->join('student_records', 'student_records.user_id', '=', 'users.id')
            ->leftJoin('my_classes', 'my_classes.id', '=', 'student_records.my_class_id')
            ->leftJoin('sections', 'sections.id', '=', 'student_records.section_id')
            ->leftJoin('class_types as departments', 'departments.id', '=', 'student_records.department_id')
            ->leftJoin('colleges', 'colleges.id', '=', 'student_records.college_id')
            ->where('student_records.grad', 0)
            ->select(
                'users.name as name',
                'users.phone as cell',
                'users.email as email',
                'student_records.id as id',
                'student_records.user_id as user_id',
                'student_records.adm_no as adm_no',
                'student_records.status as status',
                'student_records.college_id',
                'my_classes.name as class',
                'sections.name as cohort',
                'colleges.name as college',
                'departments.name as department',
                'student_records.major as major',
                'student_records.minor as minor'
            )
            ->get();

        if ($request->ajax()) {
            $alldata = DataTables::of($student)
                ->addColumn('Status', function ($student) {
                    return $student->status == 1
                        ? '<a href="#" class="btn btn-info">Active</a>'
                        : '<a href="#" class="btn btn-warning">Dropped</a>';
                })
                ->addColumn('Program', function ($student) {
                    if ($student->college_id == NULL) {
                        return "{$student->class}  {$student->cohort}";
                    }
                    return "{$student->college} - {$student->department} <br>Major: {$student->major} <br>Minor: {$student->minor}";
                })
                ->addColumn('checkbox', function ($student) {
                    return '<input type="checkbox" name="student_checkbox[]" class="student_checkbox" value="' . $student->id . '">';
                })
                ->rawColumns(['Status', 'Program', 'checkbox'])
                ->make(true);

            return $alldata;
        }

        return view('pages.support_team.bulk_transfer_student.bulk', compact('student'));
    }
}
