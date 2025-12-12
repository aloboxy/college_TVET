<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Helpers\Qs;
use App\Http\Livewire\MarkSelector;
use App\Http\Requests\Course\CourseCreate;
use App\Http\Requests\Course\CourseUpdate;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\MyClass;
use App\Models\ClassType;
use App\Models\Subject;
use Illuminate\Http\Request;


class CourseController extends Controller
{
    protected $my_class, $year, $user;
    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->my_class = $my_class;
        $this->user = $user;
        $this->year = Qs::getSetting('current_session');
    }
    public function create(Request $request)
    {
        $level = $request->get('level'); // from the dropdown
        $d['teachers'] = $this->user->getUserByType('teacher');
        $d['my_classes'] = ClassType::all();
        $d['years'] = DB::table('academic_year')->get();

        if ($level) {
            $d['subjects'] = Subject::where('level', $level)->get(); // filtered by level
            $d['selected'] = true;
        } else {
            $d['subjects'] = Subject::all(); // or $this->my_class->getAllSubjects()
            $d['selected'] = false;
        }

        $d['selected_level'] = $level; // pass selected level back to blade

        return view('pages.support_team.courses.create', $d);
    }


    public function index()
    {

        // dd($meat);
        $year = Qs::getSetting('current_session');
        $sem = Qs::getSetting('Semester');


        $d['teachers'] = $this->user->getUserByType('teacher');
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['courses'] = Course::with(['department', 'teacher'])->where('year', $year)->get();
        $d['departments'] = ClassType::all();

        return view('pages.support_team.courses.index', $d);
    }

    public function show()
    {
        $d['teachers'] = $this->user->getUserByType('teacher');
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['courses'] = $this->my_class->getAllCourses();
        $d['my_classes'] = $this->my_class->all();

        return view('pages.support_team.courses.list', $d);
    }

    public function store(CourseCreate $req)
    {
        $data = $req->all();
        $day = $data['day'];
        $data['day'] = implode(',', $day);

        $class = $data['department_id'];
        $data['department_id'] = implode(',', $class);
        $courses = Course::create($data);

        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $d['s'] = $sub = $this->my_class->findCourse($id);
        $d['sections'] = $this->my_class->getAllSections();
        $d['course'] = Course::first();
        $d['departments'] = ClassType::all();
        $d['teachers'] = $this->user->getUserByType('teacher');
        $d['selected'] = false;

        return is_null($sub) ? Qs::goWithDanger('courses.index') : view('pages.support_team.courses.edit', $d);
    }

    public function update(CourseUpdate $req, $id)
    {
        $data = $req->all();
        $day = $data['day'];
        $data['day'] = $rg = implode(',', $day);

        $class = $data['department_id'];
        $data['department_id'] = implode(',', $class);
        $this->my_class->updateCourse($id, $data);

        $sr = DB::table('enrolleds')->where('course_id', $id)->first();

        if ($sr === null) {
        } else {
            DB::table('enrolleds')->where('course_id', $id)->update([
                'teacher_id' => $req->teacher_id,
                'session' => $req->session,
                'term_id' => $req->term_id,
                'time_from' => $req->time_from,
                'time_to' => $req->time_to,
                'day' => $rg,
                'room' => $req->room
            ]);
        }

        return back()->with('flash_success', __('msg.update_ok'));
        // return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $this->my_class->deleteCourse($id);
        DB::table('enrolleds')->where('course_id', $id)->delete();
        DB::table('marks')->where('subject_id', $id)->delete();
        return back()->with('flash_success', __('msg.del_ok'));
    }


    public function course()
    {
        $years = DB::table('academic_year')->get();
        $classes = Myclass::all();
        return view('pages.support_team.courses.addstud', compact('years', 'classes'));
    }

    public function my()
    {
        return view('pages.support_team.courses.enrolled');
    }
    public function enrolledplay(MarkSelector $req)
    {
        $data = $req->only(['exam_id', 'my_class_id', 'section_id', 'subject_id']);
        $$data['year'] = $this->year;

        return redirect()->route([$req->exam_id, $req->my_class_id, $req->section_id, $req->subject_id]);
    }
}
