<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Helpers\Qs;
use App\Models\StudentRecord;
use App\Models\Course;
use App\Models\Enrolled;
use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class StudentEnrolled extends Component
{
    public $selectedClass;
    public $selectedSection;
    public $selectedStudent;
    public $selectedYear;
    public $classes;
    public $sections;
    public $years;
    public $exams;
    public $selectedExam;
    public $students;
    public $nj;
    public $term;
    public $all;
    public $courses;
    public $fg = null;
    public $selectedSubject;
    public $search = '';
    protected $listeners = ['refreshpage' => '$refresh'];

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {


        $this->years = collect();
        $this->exams = collect();
        $this->sections = collect();
        $this->students = collect();
        $this->classes = MyClass::all();
    }


    public function render()
    {
        $this->years = DB::table('academic_year')->get();
        $nowtdate = Carbon::now();
        $meat = $nowtdate->subMonth(1);
        // dd($meat);

        // $check = DB::table('student_records')->find($this->user);
        $this->courses = Enrolled::where('year', $this->selectedYear)->where('user_id', $this->selectedStudent)->get();
        // $courses = Enrolled::where('user_id',$this->selectedStudent)->where('created_at','>',$meat)->get();


        return view('livewire.student-enrolled', ['courses' => $this->courses]);
    }


    public function updatedSelectedClass($class)
    {
        $this->sections = Section::where('active', 1)->where('my_class_id', $class)->get()->sortBy('name');
        $this->nj = $class;
    }

    public function updatedSelectedSection($value)
    {
        $this->students = StudentRecord::where('my_class_id', $this->selectedClass)
            ->where('section_id', $this->selectedSection)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('adm_no', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->with('user')->get();
    }

    public function updatedSelectedExam($exam)
    {
        $this->term = Exam::find($exam);
    }

    public function updatedSelectStudent($value)
    {
        $this->students = $value;
    }


    public function updatedSelectedYear($year)
    {
        $this->exams = Exam::where('year', $year)->get();
    }
}
