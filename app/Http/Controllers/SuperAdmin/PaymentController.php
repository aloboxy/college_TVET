<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Pay;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentCreate;
use App\Http\Requests\Payment\PaymentUpdate;
use App\Models\Setting;
use App\Repositories\MyClassRepo;
use App\Repositories\PaymentRepo;
use App\Repositories\StudentRepo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\PaymentRecord;
use PDF;
use App\Models\MyClass;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Models\StudentRecord;

class PaymentController extends Controller
{
    protected $my_class, $pay, $student, $year, $term;

    public function __construct(MyClassRepo $my_class, PaymentRepo $pay, StudentRepo $student)
    {
        $this->my_class = $my_class;
        $this->pay = $pay;
        $this->year = Qs::getCurrentSession();
        $this->term = Qs::getSetting('Semester');
        $this->student = $student;

        $this->middleware('teamTAS');
    }

    public function index()
    {
        $d['selected'] = false;
        $d['years'] = DB::table('academic_year')->get();


        return view('pages.support_team.payments.index', $d);
    }

    public function show(Request $request)
    {
        // dd($request);
        $d['payments'] = $p = Payment::where('year', $request->year)->where('term_id', $request->term_id)->get();

        // if (($p->count() < 1)) {
        //     return Qs::goWithDanger('payments.index');
        // }
        $d['semester'] = DB::table('exams')->where('term', $request->term_id)->first();
        $d['selected'] = true;
        $d['my_classes'] = $this->my_class->all();
        $d['years'] = DB::table('academic_year')->get();
        $d['year'] = $request->year;

        return view('pages.support_team.payments.index', $d);
    }

    public function select_year(Request $req)
    {
        return Qs::goToRoute(['payments.show', $req->year]);
    }

    public function create()
    {
        $d['sections'] = $this->my_class->getAllSections();
        $d['years'] = DB::table('academic_year')->get();
        $d['selected'] = false;
        $d['departments'] = DB::table('class_types')->get();
        $d['my_classes'] = $this->my_class->all();
        return view('pages.support_team.payments.create', $d);
    }

    public function invoice($st_id, $year = NULL)
    {
        $sr = $student = StudentRecord::where('user_id', $st_id)->first();

    if (!$student) {
        return back()->with('error', 'Student not found.');
    }

    // Ensure the student class ID is properly formatted
    $classIds = $student->my_class_id;
    $departmentId = $student->department_id;

    // Fetch expected payments for the student's class and year
    $duePayments = DB::table('payments')
    ->where('year', $this->year)
    ->where('term_id', $this->term)
    ->where(function ($query) use ($classIds, $departmentId) {
        $query->whereRaw("FIND_IN_SET(?, my_class_id)", [$classIds])
              ->orWhere(function ($q) use ($departmentId) {
                  $q->whereNull('my_class_id')
                    ->where('department_id', $departmentId);
              });
    })
    ->get();



    // Fetch all existing payment records for the student
    $existingPaymentRecords = PaymentRecord::where('student_id', $student->user_id)
        ->where('year', $this->year)
        ->where('term_id', $this->term)
        ->pluck('payment_id')
        ->toArray(); // Get an array of existing payment IDs

    // Prepare an array to hold new payment records
    $batchInsertData = [];

    foreach ($duePayments as $payment) {
        if (!in_array($payment->id, $existingPaymentRecords)) {
            // Payment does not exist in `payment_records`, so add it
            $batchInsertData[] = [
                'student_id' => $student->user_id,
                'payment_id' => $payment->id,
                'year' => $this->year,
                'ref_no' => mt_rand(100000, 999999999),
                'paid' => 0 // Default to unpaid
            ];
        }
    }

    // Insert only if there are missing payments
    if (!empty($batchInsertData)) {
        DB::table('payment_records')->insert($batchInsertData);
    }

    // Retrieve updated payment records
    $payments = PaymentRecord::where('student_id', $student->user_id)
        ->where('year', $this->year)
        ->get();

    // Filter cleared and uncleared payments
    $cleared = $payments->where('paid', 1);
    $uncleared = $payments->where('paid', 0);

    // Return invoice view with data
    return view('pages.support_team.payments.invoice', compact('student', 'cleared', 'uncleared', 'sr'));
    }



    public function student_fees($st_id, $year = NULL)
    {
        if (!$st_id && !Qs::userIsTeamSAT()) {
            return Qs::goWithDanger();
        }

        $student = StudentRecord::where('user_id', $st_id)->first();

        // dd($student);
        $d['sr'] = $this->student->findByUserId($st_id)->first();
        $pr = PaymentRecord::where('student_id', $student->user_id)
            ->where('year', $this->year)
            ->get();

        // dd($pr);
        if (!$pr->isEmpty()) {
            $d['cleared'] = $pr->where('paid', 1);
            $d['uncleared'] = $pr->where('paid', 0);
        } else {
            $py = DB::table('payments')
                ->where('year', $this->year)
                ->whereIn('my_class_id', explode(',', $student->my_class_id))
                ->get();

            if (!$py->isEmpty()) {
                $batchInsertData = [];

                foreach ($py as $p) {
                    $batchInsertData[] = [
                        'student_id' => $student->user_id,
                        'payment_id' => $p->id,
                        'year' => $this->year,
                        'ref_no' => mt_rand(100000, 999999999)
                    ];
                }
                DB::table('payment_records')->insert($batchInsertData);
            }

            $pr = PaymentRecord::where('student_id', $student->user_id)
                ->where('year', $this->year)
                ->get();

            $d['cleared'] = $pr->where('paid', 1);
            $d['uncleared'] = $pr->where('paid', 0);
        }

        return view('pages.support_team.payments.studentfess', $d);
    }


    public function receipts($pr_id)
    {
        if (!$pr_id) {
            return Qs::goWithDanger();
        }

        try {
            $d['pr'] = $pr = $this->pay->getRecord(['id' => $pr_id])->with('receipt')->first();
        } catch (ModelNotFoundException $ex) {
            return back()->with('flash_danger', __('msg.rnf'));
        }
        // dd($pr);
        $d['receipts'] = $pr->receipt;
        $d['payment'] = $pr->payment;
        $d['sr'] = StudentRecord::where('user_id', $pr->student_id)->first();
        $d['s'] = Setting::all()->flatMap(function ($s) {
            return [$s->type => $s->description];
        });

        return view('pages.support_team.payments.receipt', $d);
    }


    public function student_receipt()
    {

        if (Auth::user()->id != $st_id) {
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }

        if (!$pr_id) {
            return Qs::goWithDanger();
        }

        try {
            $d['pr'] = $pr = $this->pay->getRecord(['id' => $pr_id])->with('receipt')->first();
        } catch (ModelNotFoundException $ex) {
            return back()->with('flash_danger', __('msg.rnf'));
        }

        $d['receipts'] = $pr->receipt;
        $d['payment'] = $pr->payment;
        $d['sr'] = $this->student->findByUserId($pr->student_id)->first();
        $d['s'] = Setting::all()->flatMap(function ($s) {
            return [$s->type => $s->description];
        });


        return view('pages.support_team.students.receipt', $d);
    }



    public function pdf_receipts($pr_id)
    {
        if (!$pr_id) {
            return Qs::goWithDanger();
        }

        try {
            $d['pr'] = $pr = $this->pay->getRecord(['id' => $pr_id])->with('receipt')->first();
        } catch (ModelNotFoundException $ex) {
            return back()->with('flash_danger', __('msg.rnf'));
        }
        $d['receipts'] = $pr->receipt;
        $d['payment'] = $pr->payment;
        $d['sr'] = $sr = $this->student->findByUserId($pr->student_id)->first();
        $d['s'] = Setting::all()->flatMap(function ($s) {
            return [$s->type => $s->description];
        });

        $pdf_name = 'Receipt_' . $pr->ref_no;

        return PDF::loadView('pages.support_team.payments.receipt', $d)->stream($pdf_name);
    }

    protected function downloadReceipt($page, $data, $name = NULL)
    {
        $path = 'receipts/file.html';
        $disk = Storage::disk('local');
        $disk->put($path, view($page, $data));
        $html = $disk->get($path);
        return PDF::loadHTML($html)->download($name);
    }

    public function pay_now(Request $req, $pr_id)
    {
        $this->validate($req, [
            'amt_paid' => 'required|numeric'
        ], [], ['amt_paid' => 'Amount Paid']);

        $pr = $this->pay->findRecord($pr_id);
        $payment = $this->pay->find($pr->payment_id);
        $d['amt_paid'] = $amt_p = $pr->amt_paid + $req->amt_paid;
        $d['balance'] = $bal = $payment->amount - $amt_p;
        $d['paid'] = $bal < 1 ? 1 : 0;

        $this->pay->updateRecord($pr_id, $d);

        $d2['amt_paid'] = $req->amt_paid;
        $d2['balance'] = $bal;
        $d2['pr_id'] = $pr_id;
        $d2['year'] = $this->year;

        $this->pay->createReceipt($d2);
        return Qs::jsonUpdateOk();
    }

    public function manage(Request $request)
    {
        $student = DB::table('users')
            ->join('student_records', 'student_records.user_id', '=', 'users.id')
            ->join('my_classes', 'my_classes.id', '=', 'student_records.my_class_id')
            ->join('sections', 'sections.id', '=', 'student_records.section_id')
            ->where('student_records.grad', 0)
            ->select(
                'users.name as name',
                'my_classes.name as class',
                'sections.name as cohort',
                'student_records.status as status',
                'student_records.user_id as user_id',
                'student_records.id as id',
                'student_records.adm_no as adm_no',
                'users.phone as cell',
                'users.email as email',
                'users.id as user_id',
            )
            ->get();
        // dd($student);

        if ($request->ajax()) {
            $alldata = DataTables::of($student)
                ->addColumn('Status', function ($student) {
                    if ($student->status == 1) {
                        return '<a href="" class="btn btn-info"><i class="glyphicon glyphicon-edit"></i>Active</a>';
                    } else {
                        return '<a href="" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i>Dropped</a>';
                    }
                })

                ->addColumn('action', function ($student) {
                    $actionButtons = '';

                    // View student button
                    // If user is Super Admin, add Edit and Reset Password buttons
                    if (Qs::getTeamAccount()) {
                        $actionButtons .= '<a href=" ' . route('payments.invoice', [Qs::hash($student->user_id)]) . '" class="icon-copy btn btn-success">Current Semester</a>';
                    }
                    // View Grade Sheet button
                    $actionButtons .= '<a href="' . route('payments.year_selector', Qs::hash($student->user_id)) . '" class="icon-check btn btn-primary" title="Show Year">Payment Per Year/Semester</a>';

                    return $actionButtons;
                })
                ->rawColumns(['Status', 'action'])
                ->make(true);
            return $alldata;
        }
        return view('pages.support_team.payments.manage', compact('student'));
    }

    public function select_class(Request $req)
    {
        $this->validate($req, [
            'my_class_id' => 'required|exists:my_classes,id'
        ], [], ['my_class_id' => 'Class']);

        $wh['my_class_id'] = $class_id = $req->my_class_id;

        $pay1 = $this->pay->getPayment(['my_class_id' => $class_id, 'year' => $this->year])->get();
        $pay2 = $this->pay->getGeneralPayment(['year' => $this->year])->get();
        $payments = $pay2->count() ? $pay1->merge($pay2) : $pay1;
        $students = $this->student->getRecord($wh)->get();

        if ($payments->count() && $students->count()) {
            foreach ($payments as $p) {
                foreach ($students as $st) {
                    $pr['student_id'] = $st->user_id;
                    $pr['payment_id'] = $p->id;
                    $pr['year'] = $this->year;
                    $rec = $this->pay->createRecord($pr);
                    $rec->ref_no ?: $rec->update(['ref_no' => mt_rand(100000, 99999999)]);
                }
            }
        }

        return Qs::goToRoute(['payments.manage', $class_id]);
    }

    public function store(PaymentCreate $req)
    {
        $data = $req->all();
        // dd($data);

        $class = $data['my_class_id'];
        $data['my_class_id'] = implode(',', $class);
        $data['year'] = $req->year;
        $data['section_id'] = $req->section_id;
        $data['term_id'] = $req->term_id;
        $data['ref_no'] = Pay::genRefCode();
        $this->pay->create($data);

        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $d['payment'] = $pay = $this->pay->find($id);
        $d['my_classes'] = $this->my_class->all();

        return is_null($pay) ? Qs::goWithDanger('payments.index') : view('pages.support_team.payments.edit', $d);
    }

    public function update(PaymentUpdate $req, $id)
    {
        // dd($req);
        $data = $req->all();
        $class = $data['my_class_id'];
        $data['my_class_id'] = implode(',', $class);
        $this->pay->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $this->pay->find($id)->delete();

        DB::table('payment_records')->where('payment_id', $id)->delete();

        return Qs::deleteOk('payments.index');
    }

    public function reset_record($id)
    {
        $pr['amt_paid'] = $pr['paid'] = $pr['balance'] = 0;
        $this->pay->updateRecord($id, $pr);
        $this->pay->deleteReceipts(['pr_id' => $id]);

        return back()->with('flash_success', __('msg.update_ok'));
    }




    ///////Student payment year selector
    protected function verifyStudentExamYear($student_id, $year = null)
    {
        $years = $this->exam->getExamYears($student_id);
        $student_exists = $this->student->exists($student_id);

        if (!$year) {
            if ($student_exists && $years->count() > 0) {
                $d = ['years' => $years, 'student_id' => Qs::hash($student_id)];

                return view('pages.support_team.marks.select_year', $d);
            }

            return $this->noStudentRecord();
        }

        return ($student_exists && $years->contains('year', $year)) ? true : false;
    }

    protected function noStudentRecord()
    {
        return redirect()->route('dashboard')->with('flash_danger', __('msg.srnf'));
    }


    public function payment_year_selector($student_id)
    {
        $d['selected'] = false;
        $d['student_id'] = $student_id;

        $d['years'] = DB::table('academic_year')->get();

        return view('pages.support_team.payments.year_selector_payments', $d);
    }

    public function invoice_year(Request $request)
    {
        // dd($request);
        // year
        // term_id
        // student_id

        $d['sr'] = $students = $this->student->findByUserId($request->student_id)->first();

        $pr = PaymentRecord::where('student_id', $request->student_id)
            ->where('year', $request->year)
            ->get();

        if (!$pr->isEmpty()) {
            $d['cleared'] = $pr->where('paid', 1);
            $d['uncleared'] = $pr->where('paid', 0);
        } else {
            $py = DB::table('payments')
                ->where('year', $request->year)
                ->whereIn('my_class_id', explode(',', $students->my_class_id))
                ->get();

            if (!$py->isEmpty()) {
                $batchInsertData = [];

                foreach ($py as $p) {
                    $batchInsertData[] = [
                        'student_id' => $students->user_id,
                        'payment_id' => $p->id,
                        'year' => $request->year,
                        'ref_no' => mt_rand(100000, 999999999)
                    ];
                }
                DB::table('payment_records')->insert($batchInsertData);
            }

            $pr = PaymentRecord::where('student_id', $students->user_id)
                ->where('year', $request->year)
                ->get();

            $d['cleared'] = $pr->where('paid', 1);
            $d['uncleared'] = $pr->where('paid', 0);
        }
        return view('pages.support_team.payments.invoice', $d);
    }


    public function bill()
    {
        $d['classes'] = $this->my_class->all();
        $d['years'] = DB::table('academic_year')->get();
        return view('pages.support_team.payments.class_year_bill', $d);
    }

    public function generalbill(Request $req)
    {

        // dd($req);
        $this->validate($req, [
            'my_class_id' => 'required|exists:my_classes,id'
        ], [], ['my_class_id' => 'Class']);

        $wh['my_class_id'] = $class_id = $req->my_class_id;
        $wh['section_id'] = $req->section_id;
        $wh['session'] = $req->year;
        // $wh['semester'] = $req->term_id;

        // dd($wh);

        // $payments = Payment::where('year', $req->year)
        //     ->whereIn('my_class_id', explode(',', $req->my_class_id))
        //     ->get();

        $payments = Payment::where('year', $this->year)
            ->where(function ($query) use ($req) {
                foreach (explode(',', $req->my_class_id) as $classId) {
                    $query->orWhereRaw("FIND_IN_SET(?, my_class_id)", [$classId]);
                }
            })
            ->get();

        // dd($payments);

        $students = $this->student->getRecord($wh)->get();
        // dd($students);

        if ($payments->count()  && $students->count()) {
            foreach ($payments as $p) {
                foreach ($students as $st) {
                    // Check if a record already exists for this student, payment, and year
                    $existingRecord = PaymentRecord::where([
                        'student_id' => $st->user_id,
                        'payment_id' => $p->id,
                        'year' => $req->year
                    ])->first();

                    // If no record exists, create a new one
                    if (!$existingRecord) {
                        $pr['student_id'] = $st->user_id;
                        $pr['payment_id'] = $p->id;
                        $pr['year'] = $this->year;
                        $rec = $this->pay->createRecord($pr);
                        $rec->ref_no ?: $rec->update(['ref_no' => mt_rand(100000, 99999999)]);
                    }
                }
            }
        }
        return back()->with('flash_success', 'Students Billed Successfully');
    }
}