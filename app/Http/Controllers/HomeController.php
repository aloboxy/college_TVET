<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\UserRepo;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\ClassType;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $user;
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }


    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.privacy_policy', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.terms_of_use', $data);
    }

    public function dashboard()
    {

        $d=[];
        if(Qs::userIsTeamSAT())
        {
            $d['users'] = $this->user->getAll();
            $d['student'] = StudentRecord::with('my_class')->get();
            $d['class'] = MyClass::with('class_type')->get();
            $d['classtype'] = ClassType::all();
        }
        return view('pages.support_team.dashboard', $d);
    }
}
