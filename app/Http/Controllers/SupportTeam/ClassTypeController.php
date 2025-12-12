<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\Department\DeptCreate;
use App\Http\Requests\Department\DeptUpdate;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Models\college;
use App\Http\Controllers\Controller;

class ClassTypeController extends Controller
{
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->middleware('can:department.manage');

        $this->my_class = $my_class;
        $this->user = $user;
    }

    public function index()
    {
        $d['names'] = $this->my_class->allclasstype();
        $d['teachers'] = $this->user->getUserByType('teacher');
        $d['college']= college::all();
        return view('pages.support_team.department.index', $d);
    }

    public function store(DeptCreate $req)
    {
        $data = $req->only(['name', 'code','program','teacher_id','college_id','total_credit','class_base']);
        $this->my_class->createclasstype($data);

        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $d['c'] = $c = $this->my_class->findclasstype($id);
        $d['college']= college::all();
        $d['teachers'] = $this->user->getUserByType('teacher');
        return is_null($c) ? Qs::goWithDanger('department.index') : view('pages.support_team.department.edit', $d) ;
    }

    public function update(DeptUpdate $req, $id)
    {
        // dd($req);
        $data = $req->only(['name','code','program','teacher_id','college_id','total_credit','class_base']);
        $this->my_class->updateclasstype($id, $data);

        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $this->my_class->deleteclasstype($id);
        return back()->with('flash_success', __('msg.del_ok'));
    }

}
