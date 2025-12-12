<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\Subject\SubjectCreate;
use App\Http\Requests\Subject\SubjectUpdate;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Models\Term;
use App\Http\Controllers\Controller;
use App\Models\ClassType;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',]]);
        $this->middleware('super_admin', ['only' => ['destroy',]]);

        // Granular Permissions
        $this->middleware('can:academics.manage', ['only' => ['index']]);
        $this->middleware('can:subjects.create', ['only' => ['store']]);
        $this->middleware('can:subjects.edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:subjects.delete', ['only' => ['destroy']]);

        $this->my_class = $my_class;
        $this->user = $user;
    }

    public function index()
    {
        $d['my_classes'] = ClassType::all();
        $d['teachers'] = $this->user->getUserByType('teacher');
        $d['subjects'] = $this->my_class->getAllSubjects();

        return view('pages.support_team.subjects.index', $d);
    }

    public function store(SubjectCreate $req)
    {
        $data = $req->all();
        $class = $data['department_id'];
        $data['department_id'] = implode(',', $class);
        $this->my_class->createSubject($data);

        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $d['s'] = $sub = $this->my_class->findSubject($id);
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['my_classes'] = ClassType::all();
        $d['teachers'] = $this->user->getUserByType('teacher');

        // dd($d);

        return is_null($sub) ? Qs::goWithDanger('subjects.index') : view('pages.support_team.subjects.edit', $d);
    }

    public function update(SubjectUpdate $req, $id)
    {
        // Handle the toggle button value
        $clinical = $req->has('clinical') && $req->clinical == 'on' ? 1 : 0;

        // Merge the toggle value into the request data
        $data = $req->all();

        $class = $data['department_id'];
        $data['department_id'] = implode(',', $class);
        $data['clinical'] = $clinical;

        // Debugging: Check the data before updating
        // dd($data);

        // Update the subject
        $this->my_class->updateSubject($id, $data);

        // Redirect back with a success message
        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function destroy($id)
    {
        $this->my_class->deleteSubject($id);
        DB::table('marks')->where('subject_id', $id)->delete();
        DB::table('enrolleds')->where('subject_id', $id)->delete();
        return back()->with('flash_success', __('msg.del_ok'));
    }
}
