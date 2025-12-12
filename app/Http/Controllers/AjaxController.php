<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\ExamRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;


class AjaxController extends Controller
{
    protected $loc, $my_class, $exam;

    public function __construct(LocationRepo $loc, MyClassRepo $my_class, ExamRepo $exam)
    {
        $this->loc = $loc;
        $this->exam = $exam;
        $this->my_class = $my_class;
    }

    public function get_lga($state_id)
    {
//        $state_id = Qs::decodeHash($state_id);
//        return ['id' => Qs::hash($q->id), 'name' => $q->name];

        $lgas = $this->loc->getLGAs($state_id);
        return $data = $lgas->map(function($q){
            return ['id' => $q->id, 'name' => $q->name];
        })->all();
    }

    public function get_class_sections($class_id)
    {
        $sections = $this->my_class->getClassSections($class_id);
        return $sections = $sections->map(function($q){
            return ['id' => $q->id, 'name' => $q->name];
        })->all();
    }

    public function get_class_subjects($class_id)
    {
        $sections = $this->my_class->getClassSections($class_id);
        $subjects = $this->my_class->findSubjectByClass($class_id);

        if(Qs::userIsTeacher()){
            $subjects = $this->my_class->findSubjectByTeacher(Auth::user()->id)->where('my_class_id', $class_id);
        }

        $d['sections'] = $sections->map(function($q){
            return ['id' => $q->id, 'name' => $q->name];
        })->all();
        $d['subjects'] = $subjects->map(function($q){
            return ['id' => $q->id, 'name' => $q->name];
        })->all();

        return $d;
    }


    public function student_status(Request $request)
    {
        // return json_encode($request->all());

        $stu_status = StudentRecord::where('user_id', $request->studentId)->first();

        if ($stu_status->status == 1) {
            DB::table('users')->where('id', $request->studentId)->update(['status' => 0]);
            StudentRecord::where('user_id', $request->studentId)->update(['status' => 0]);
        } else {
            DB::table('users')->where('id', $request->studentId)->update(['status' => 1]);
            StudentRecord::where('user_id', $request->studentId)->update(['status' => 1]);
        }

       return response()->json([
        'msg' => 'Student status updated successfully',
        'status' => 'success'
    ]);
    }



}
