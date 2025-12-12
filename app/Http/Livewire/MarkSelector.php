<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Helpers\Qs;
use App\Models\Course;
use Auth;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MarkSelector extends Component
{
    public $sections;
    public $exams;
    public $classes;
    public $subjects;
    public $years;
    public $term;
    public $user;
    public $nj;
    public $selectedClass = null;
    public $selectedSection = null;
    public $selectedExam;
    public $selectedYear = null;
    public $selectedSubject = null;



    public function mount()
    {
        $this->years = collect();
        $this->sections = collect();
        $this->exams = collect();
        $this->subjects = collect();
        $this->classes = MyClass::where('active', 1)->get()->sortBy('name');
    }
    public function render()
    {
        $this->years = DB::table('academic_year')->get();
        return view('livewire.mark-selector');
    }


    public function updatedSelectedClass($class)
    {
        if ($this->term == null) {
            session()->flash('message', 'Please select this Field First');
        } else {

            $this->sections = Section::where('active', 1)->where('my_class_id', $class)->get()->sortBy('name');
            $this->nj = $class;
        }
    }

    public function updatedSelectedSubject($value) {}


    public function updatedSelectedSection($sec)
    {


        if ($this->selectedClass == null) {
            session()->flash('message', 'Please select this Field First');
        }
        $this->user = Auth::user()->id;
        $fl = DB::table('sections')->find($sec);
        if ($fl != null) {

            $kl = $fl->name;

            $nowtdate = Carbon::now();
            $meat = $nowtdate->addMonths(1);
        } else {
            session()->flash('message', 'Please select this Field First');
        }

        if (Qs::userIsTeacher()) {

            $this->subjects = Course::where('term_id', $this->term->term)
                ->whereRaw("FIND_IN_SET(?, my_class_id)", [$this->selectedClass])
                ->orWhere(function ($query) {
                    $query->where('year', $this->selectedYear)
                        ->where('term_id', $this->term->term);
                })
                ->where('teacher_id', $this->user)
                ->get();
        } else {

            $this->subjects = Course::whereRaw("FIND_IN_SET(?, my_class_id)", [$this->selectedClass])
                ->where('section_id', $this->selectedSection)
                ->where('year', $this->selectedYear)
                ->where('term_id', $this->term->term)
                ->get();
        }
    }

    public function updatedSelectedYear($years)
    {
        $this->exams = Exam::where('year', $years)->get();
    }


    public function updatedSelectedExam($exam)
    {
        $this->term = Exam::find($exam);
    }
}
