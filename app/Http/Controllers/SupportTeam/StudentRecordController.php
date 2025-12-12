<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Mk;
use App\Http\Requests\Student\StudentRecordCreate;
use App\Http\Requests\Student\StudentRecordUpdate;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Models\college;
use App\Models\ClassType;
use App\Models\Major;
use App\Models\Minor;
use App\Models\StudentRecord;
use App\User;

class StudentRecordController extends Controller
{
    protected $loc, $my_class, $user, $student;

    public function __construct(LocationRepo $loc, MyClassRepo $my_class, UserRepo $user, StudentRepo $student)
    {
        $this->middleware('teamSA', ['only' => ['edit', 'update', 'reset_pass', 'create', 'store', 'graduated']]);
        $this->middleware('super_admin', ['only' => ['destroy']]);

        // Granular Permissions
        $this->middleware('can:students.create', ['only' => ['create', 'store']]);
        $this->middleware('can:students.edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:students.view', ['only' => ['listByClass']]);
        $this->middleware('can:students.graduate', ['only' => ['graduated', 'not_graduated']]);

        $this->loc = $loc;
        $this->my_class = $my_class;
        $this->user = $user;
        $this->student = $student;
    }

    public function reset_pass($st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        $data['password'] = Hash::make('student');
        $this->user->update($st_id, $data);
        return back()->with('flash_success', __('msg.p_reset'));
    }

    public function create()
    {
        $data['my_classes'] = $this->my_class->all();
        $data['college'] = college::all();
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();
        $data['years'] = DB::table('academic_year')->get();
        return view('pages.support_team.students.add', $data);
    }

    public function store(StudentRecordCreate $req)
    {
        $check = StudentRecord::where(['adm_no' => $req->adm_no])->first();
        $user_check = User::where(['username' => $req->adm_no])->first();

        if($check == null && $user_check != null){
            User::where(['username' => $req->adm_no])->delete();
        }
        
        DB::beginTransaction();
        try {
            $data = $req->only(Qs::getUserRecord());
            $sr = $req->only(Qs::getStudentData());
            if ($req->program == 'Diploma' || $req->program == 'Certificate') {
                $ct = $this->my_class->findTypeByClass($req->my_class_id)->code;
            }
            /* $ct = ($ct == 'J') ? 'JSS' : $ct;
             $ct = ($ct == 'S') ? 'SS' : $ct;*/

            $data['user_type'] = 'student';
            $data['name'] = ucwords($req->name);
            $data['code'] = strtoupper(Str::random(10));
            $data['password'] = Hash::make('student');
            $data['photo'] = Qs::getDefaultUserImage();
            $adm_no = $req->adm_no;
            $data['username'] = strtoupper(($adm_no ?: mt_rand(1000, 99999)));

            if ($req->hasFile('photo')) {
                $photo = $req->file('photo');
                $f = Qs::getFileMetaData($photo);
                $f['name'] = 'photo.' . $f['ext'];
                $f['path'] = $photo->storeAs(Qs::getUploadPath('student') . $data['code'], $f['name']);
                $data['photo'] = asset('storage/' . $f['path']);
            }

            $user = $this->user->create($data); // Create User

            $sr['adm_no'] = $data['username'];
            $sr['my_parent'] = $req->my_parent;
            $sr['user_id'] = $user->id;
            $sr['session'] = $req->year;

            $this->student->createRecord($sr);
            DB::commit();
            return Qs::jsonStoreOk();
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error for debugging
            \Log::error('Student creation failed: ' . $e->getMessage());
            // 'error' => $e->getMessage() // Optional: for debugging
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listByClass(Request $request)
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
                ->addColumn('action', function ($student) {
                    $idHash = Qs::hash($student->id);
                    $userIdHash = Qs::hash($student->user_id);

                    $buttons = '<a href="' . route('students.show', $idHash) . '" class="icon-eye btn btn-yellow" style="background-color: green;" title="View Student"></a>';

                    if (Qs::userIsTeamSA()) {
                        $buttons .= '<a href="' . route('students.edit', $idHash) . '" class="icon-pencil btn btn-warning" title="Edit Student"></a>';
                        $buttons .= '<a href="' . route('students.change_college', $idHash) . '" class="icon-sync btn btn-blue" title="Change of College"></a>';
                        $buttons .= '<a href="' . route('st.reset_pass', $userIdHash) . '" class="icon-lock btn btn-secondary" title="Reset Password"></a>';
                        
                        if(Auth::user()->can('students.delete')){
                            $buttons .= '<a id="' . $userIdHash . '" onclick="confirmDelete(this.id)" href="#" class="icon-trash btn btn-danger" title="Delete Student"></a>';
                            $buttons .= '<form method="POST" id="item-delete-' . $userIdHash . '" action="' . route('students.destroy', $userIdHash) . '" class="hidden"></form>';
                        }
                    }
                    $buttons .= '<a target="_blank" href="' . route('marks.year_selector', $userIdHash) . '" class="icon-check btn btn-primary" title="View Grade Sheet"></a>';
                    $buttons .= '<a target="_blank" href="' . route('students.ledger', $userIdHash) . '" class="icon-book btn btn-primary" title="View Ledger"></a>';
                    return $buttons;
                })
                ->rawColumns(['Status', 'Program', 'action'])
                ->make(true);

            return $alldata;
        }

        return view('pages.support_team.students.list', compact('student'));
    }



    public function groupBy($class_id)
    {
        $data['my_class'] = $mc = $this->my_class->getMC(['id' => $class_id])->first();
        $data['students'] = $this->student->findStudentsByClass($class_id);
        $data['sections'] = $this->my_class->getClassSections($class_id);

        return is_null($mc) ? Qs::goWithDanger() : view('pages.support_team.students.group', $data);
    }

    public function graduated()
    {
        $data['my_classes'] = $this->my_class->all();
        $data['students'] = $this->student->allGradStudents();

        return view('pages.support_team.students.graduated', $data);
    }

    public function not_graduated($sr_id)
    {
        $d['grad'] = 0;
        $d['grad_date'] = NULL;
        $d['session'] = Qs::getSetting('current_session');
        $this->student->updateRecord($sr_id, $d);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function show($sr_id)
    {
        // dd($sr_id);
        $sr_id = Qs::decodeHash($sr_id);
        if (!$sr_id) {
            return Qs::goWithDanger();
        }

        $data['sr'] = $this->student->getRecord(['id' => $sr_id])->first();

        /* Prevent Other Students/Parents from viewing Profile of others */
        if (Auth::user()->id != $data['sr']->user_id && !Auth::user()->can('students.view') && !Qs::userIsMyChild($data['sr']->user_id)) {
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }

        return view('pages.support_team.students.show', $data);
    }

    public function edit($sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if (!$sr_id) {
            return Qs::goWithDanger();
        }

        $data['sr'] = $this->student->getRecord(['id' => $sr_id])->first();
        $data['my_classes'] = $this->my_class->all();
        $data['college'] = college::all();
        // $data['parents'] = $this->user->getUserByType('parent');
        $data['department'] = ClassType::all();
        $data['major'] = Major::all();
        $data['minor'] = Minor::all();
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();
        return view('pages.support_team.students.edit', $data);
    }

    public function update(StudentRecordUpdate $req, $sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if (!$sr_id) {
            return Qs::goWithDanger();
        }

        $sr = $this->student->getRecord(['id' => $sr_id])->first();
        $d = $req->only(Qs::getUserRecord());
        $d['name'] = ucwords($req->name);
        $d['my_parent'] = $req->my_parent;
        $d['username'] = $req->adm_no;

        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath('student') . $sr->user->code, $f['name']);
            $d['photo'] = asset('storage/' . $f['path']);
        }

        $this->user->update($sr->user->id, $d); // Update User Details

        $srec = $req->only(Qs::getStudentData());

        $this->student->updateRecord($sr_id, $srec); // Update St Rec

        /*** If Class/Section is Changed in Same Year, Delete Marks/ExamRecord of Previous Class/Section ****/
        // Mk::deleteOldRecord($sr->user->id, $srec['my_class_id']);

        return Qs::jsonUpdateOk();
    }

    public function destroy($st_id)
    {
        // dd($st_id);
        $st_id = Qs::decodeHash($st_id);
        if (!$st_id) {
            return Qs::goWithDanger();
        }

        $sr = $this->student->getRecord(['user_id' => $st_id])->first();
        $path = Qs::getUploadPath('student') . $sr->user->code;
        Storage::exists($path) ? Storage::deleteDirectory($path) : false;

        $this->user->delete($sr->user->id);
        DB::table('student_records')->delete($sr->id);
        DB::table('enrolleds')->where('user_id', $sr->user->id)->delete();

        return back()->with('flash_success', __('msg.del_ok'));
    }

    public function student_change_depart($st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        if (!$st_id) {
            return Qs::goWithDanger();
        }

        $college = college::all();
        $student = StudentRecord::find($st_id);
        $my_classes =$this->my_class->all();

        return view('pages.support_team.students.change_college', compact('student', 'college','my_classes'));
    }

    public function student_change_depart_store(Request $request, $st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        if (!$st_id) {
            return Qs::goWithDanger();
        }
// dd($request->all());
        $student = StudentRecord::find($st_id);
        $student->college_id = $request->college_id;
        $student->department_id = $request->department_id ?? null;
        $student->major = $request->major ?? null;
        $student->minor = $request->minor ?? null;
        $student->my_class_id = $request->my_class_id ?? null;
        $student->section_id = $request->section_id ?? null;

       $student->save();
        return Qs::jsonUpdateOk();
    }

}
