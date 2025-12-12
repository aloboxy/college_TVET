<?php

namespace App\Http\Controllers\SupportTeam;
use App\Http\Controllers\Controller;
use App\Helpers\Qs;
use App\Models\Access;
use App\Models\Exam;
use App\Models\StudentRecord;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $my_class, $exam, $student, $year, $user, $mark;

    public function __construct(MyClassRepo $my_class, ExamRepo $exam, StudentRepo $student)
    {
        $this->exam = $exam;
        $this->student = $student;
        $this->my_class = $my_class;
        $this->year = Qs::getSetting('current_session');
    }
    public function index()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['selected'] = false;

        return view('pages.support_team.access.index', $d);

    }

    public function selector(Request $req)
    {

        $data = $req->only(['exam_id', 'my_class_id', 'section_id', 'year']);
        $like = $req->only(['my_class_id', 'section_id']);
        $d2 = $req->only(['exam_id']);
        $d = $req->only(['my_class_id', 'section_id']);
        $d['session'] = $data['year'] = $d2['year'] = $req->year;
        $like['session'] = $data['year'];
        $term = DB::table('exams')->where('id', $req->exam_id)->first();
        // dd($term->term);
        // $students = Enrolled::where('course_id',$req->course_id)->get();


        $students = StudentRecord::where('my_class_id', $req->my_class_id)->where('section_id', $req->section_id)->get();

        // dd($req->section_id);
        if ($students->count() < 1) {
            return back()->with('pop_warning', __('msg.nsec'));
        }

        foreach ($students as $s) {
            $data['student_id'] = $d2['student_id'] = $s->user_id;
            $check = DB::table('access')
                ->where('student_id', $s->user_id)
                ->first();



            if ($check == null) {
                Access::create([
                    'student_id' => $s->user_id,
                    'section_id' => $req->section_id,
                    'my_class_id' => $req->my_class_id,
                ]);
            } else {

            }

        }

        return redirect()->route('access.manage', [$req->exam_id, $req->my_class_id, $req->section_id, $this->year]);
    }

    public function manage($exam_id, $class_id, $section_id, $year)
    {
        $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'year' => $year];

        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['access'] = Access::
            where('section_id', $section_id)
            ->where('my_class_id', $class_id)
            ->where('access', 1)
            ->get();


        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['year'] = $year;
        $d['selected'] = true;

        return view('pages.support_team.access.manage', $d);

    }

    public function update(Request $req, $exam_id, $class_id, $section_id, $year)
    {
        $access = DB::table('access')
            ->where('my_class_id', $class_id)
            ->where('section_id', $section_id)
            ->get();

        foreach ($access as $mk) {
            $p = 'p-' . $mk->id;
            $p = $req->$p;
            $d['access'] = $p;
            DB::table('access')->where('id', $mk->id)->update(['access' => $p]);

        }

        return Qs::jsonUpdateOk();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //         $duplicates = DB::table('access') // replace table by the table name where you want to search for duplicated values
//         ->select('id', 'student_id') // name is the column name with duplicated values
//         ->where('access')
//         ->whereIn('student_id', function ($q){
//           $q->select('student_id')
//           ->from('access')
//           ->groupBy('student_id')
//           ->havingRaw('COUNT(*) > 1');
//         })
//         ->orderBy('student_id')
//         ->orderBy('id') // keep smaller id (older), to keep biggest id (younger) replace with this ->orderBy('id', 'desc')
//         ->get();



        //         $value = "";

        // // loop throuht results and keep first duplicated value
// foreach ($duplicates as $duplicate) {
//   if($duplicate->student_id === $value)
//   {
//     DB::table('access')->where('id', $duplicate->id)->delete(); // comment out this line the first time to check what will be deleted and keeped
//     echo "$duplicate->student_id with id $duplicate->id deleted! \n";
//   }
//   else
//     echo "$duplicate->student_id with id $duplicate->id keeped \n";
//   $value = $duplicate->student_id;
// }

        // DB::table('access')->where('access')->update([
//      'access'=>0]);
// echo "Done chief";


        // return view('pages.support_team.access.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Access  $access
     * @return \Illuminate\Http\Response
     */
    public function show(Access $access)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Access  $access
     * @return \Illuminate\Http\Response
     */
    public function edit(Access $access)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Access  $access
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Access  $access
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {

        DB::table('access')->delete();

        return redirect()->route('dashboard')->with('flash_success', __('msg.access'));

    }
}
