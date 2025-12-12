<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Auth;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Mark;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\Qs;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamRecord;
use App\Models\StudentRecord;
use Livewire\WithPagination;


class FailedPass extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    Public $status;
    public $sections;
    public $exams;
    public $classes;
    public $subjects;
    public $years;
    public $class_id;
    public $exam_id;
    public $section_id;
    public $type;
    public $term;
    public $user;
    public $nj;
    public $mark =[];
    public $selectedClass = null;
    public $selectedStatus = null;
    public $selectedSection = null;
    public $selectedExam;
    public $selectedYear = null;
    public $selectedSubject = null;
    public $students = null;
    public $liststudents = [];



   public function mount()
   {
        $this->liststudents = null;
        $this->years = collect();
        $this->sections = collect();
        $this->exams = collect();
        $this->students = collect();
        $this->subjects = collect();
        $this->classes= MyClass::where('active',1)->get()->sortBy('name');
   }

    public function render()
    {
        $this->years = DB::table('academic_year')->get();

        return view('livewire.failed-pass', ['years'=>$this->years,'sections'=>$this->sections, 'liststudents'=>$this->liststudents, 'mark'=>$this->mark, 'class_id'=>$this->class_id, 'exam_id'=> $this->exam_id, 'section_id'=>$this->section_id, 'type'=>$this->type]);
    }


    public function updatedSelectedClass($class)
    {
        $this->class_id = $class;

        if($this->term == null)
        {
            session()->flash('message','Please select this Field First' );
        }

        else{

        $this->sections = Section::where('active',1)->where('my_class_id',$class)->get()->sortBy('name');
       $this->nj =$class;
         }
    }

    public function updatedSelectedSection($value)

    {
        $this->section_id = $value;
        $fl = DB::table('sections')->find($value);
    }



    public function updatedSelectedStatus($value)
    {
        $this->type = $value;

        $this->students = ExamRecord::join('student_records', 'student_records.user_id','=','exam_records.student_id' )
                    ->where('student_records.my_class_id', $this->selectedClass)
                    ->where('student_records.section_id',$this->selectedSection)
                    ->where('exam_records.exam_id', $this->selectedExam)
                    ->where('exam_records.failed',$value)
                    ->get();
    }

    public function updatedSelectedYear($years)
    {
        $this->exams = Exam::where('year', $years)->get();
    }

    public function updatedSelectedExam($exam)
    {
        $this->exam_id = $exam;
        $this->term = Exam::find($exam);
    }



}
