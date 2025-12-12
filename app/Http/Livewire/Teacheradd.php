<?php

namespace App\Http\Livewire;

use App\Models\ClassType;
use Livewire\Component;
use App\Models\StudentRecord;
use App\Models\Course;
use App\Models\Enrolled;
use App\Models\Exam;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\User;

class Teacheradd extends Component
{
    use WithPagination;

    public $selectedClass;
    public $selectedSection;
    public $classes;
    public $sections;
    public $years;
    public $exams;
    public $selectedExam;
    public $selectedYear;
    public $nj;
    public $term;
    public $selectedTerm;
    public $all;
    public $fg = null;
    public $subjects;
    public $selectedSubject;
    public $search = '';
    public $search2 = '';
    public $selectedDepartment;
    public $departments;
    public $selectedLevel;
  
    public $levels = ['Freshmen', 'Sophomore', 'Junior', 'Senior'];

    protected $listeners = ['refreshpage' => '$refresh'];
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->years = collect();
        $this->exams = collect();
        $this->sections = collect();
        $this->subjects = collect();
        $this->departments = collect();
    }

    public function render()
    {
        $this->years = DB::table('academic_year')->get();
        $this->departments = ClassType::all();
    
        try {
    
            if ($this->selectedYear && $this->selectedLevel && $this->selectedDepartment && $this->selectedSubject) {
                $enrolledStudents = $this->getEnrolledStudents();
                $studentsNotEnrolled = $this->getStudentsNotEnrolled();
            }
            else{
                $enrolledStudents = collect();
                $studentsNotEnrolled = collect();
            }
    
            return view('livewire.teacheradd', [
                'com' => $enrolledStudents,
                'students' => $studentsNotEnrolled,
                'year' => $this->selectedYear
            ]);
        } catch (\Exception $e) {
            Log::error('Error in Teacheradd render: ' . $e->getMessage());
            return view('livewire.teacheradd', [
                'com' => collect(),
                'students' => collect()
            ]);
        }
    }

    public function updatedSelectedClass($class)
    {
        $this->sections = Section::where('active', 1)
            ->where('my_class_id', $class)
            ->get()
            ->sortBy('name');
        $this->nj = $class;
    }

    public function updatedSelectedSection($section) {}

    public function updatedSelectedExam($exam)
    {
        $this->term = DB::table('exams')
            ->where('id', $exam)
            ->first()
            ->term;
    }

    public function updatedSelectedDepartment($department)
    {
        $this->departments = $department;
    }

    public function updatedSelectedSubject($subject)
    {
        $this->selectedSubject = $subject;
        $course = Course::find($subject);
        $this->fg = $course->subject_id ?? null;

    }

    public function updatedSelectedYear($year)
    {
        $this->exams = Exam::where('year', $year)->get();
    }

    public function updatedSelectedLevel($level)
    {
        $this->subjects = Course::where('year', $this->selectedYear)
            ->where('term_id', $this->term)
            ->where('level', $level)
            ->whereRaw("FIND_IN_SET(?, department_id)", $this->selectedDepartment)
            ->get();
    }

    public function enrolled($id)
    {
        $alreadyEnrolled = Enrolled::where('year', $this->selectedYear)
            ->where('term_id', $this->term)
            ->where('course_id', $this->selectedSubject)
            ->where('user_id', $id)
            ->exists();

        if ($alreadyEnrolled) {
            $this->dispatchBrowserEvent('course-flash', [
                'message' => 'Student is already enrolled in this course.',
                'type' => 'error'
            ]);
            return;
        }

        $user = StudentRecord::with('user')->find($id);
        $course = Course::find($this->selectedSubject);

        if (!$user || !$course) {
            $this->dispatchBrowserEvent('course-flash', [
                'message' => 'Invalid student or course selection.',
                'type' => 'error'
            ]);
            return;
        }

        $currentEnrollmentCount = Enrolled::where('course_id', $course->id)
            ->where('year', $this->selectedYear)
            ->count();

        if ($currentEnrollmentCount >= $course->capacity) {
            $this->dispatchBrowserEvent('course-flash', [
                'message' => 'Course capacity has been reached.',
                'type' => 'error'
            ]);
            return;
        }

        $day = is_array($course->day) ? implode(',', $course->day) : $course->day;

       try {
        DB::table('enrolleds')->insert([    
            'course_id' => $this->selectedSubject,
            'user_id' => $user->user->id,
            'teacher_id' => $course->teacher_id,
            'session' => $course->session,
            'subject_id' => $course->subject_id,
            'term_id' => $this->term,
            'time_from' => $course->time_from,
            'time_to' => $course->time_to,
            'year' => $this->selectedYear,
            'day' => $day,
            'room' => $course->room
        ]);

        $course->increment('total');
        $this->dispatchBrowserEvent('course-flash', [
            'message' => 'Student Enrolled',
            'type' => 'success'
        ]);
       } catch (\Exception $e) {
            $this->dispatchBrowserEvent('course-flash', [
                'message' => 'Failed to enroll student: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }
    public function delete($id)
    {
        $enrolledRecord = Enrolled::find($id);

        if (!$enrolledRecord) {
            $this->dispatchBrowserEvent('course-flash', [
                'message' => 'Enrollment record not found.',
                'type' => 'success'
            ]);
            return;
        }

        $course = Course::find($enrolledRecord->course_id);

        if ($course) {
            $course->decrement('total', 1);
        }

        Enrolled::destroy($id);
        $this->dispatchBrowserEvent('course-flash', [
            'message' => 'Course Dropped',
            'type' => 'success'
        ]);
        return;
    }


    private function getStudentsNotEnrolled()
    {
        return User::query()
        ->where('status', 1)
        ->whereNotExists(function ($subQuery) {
            $subQuery->select(DB::raw(1))
                ->from('enrolleds')
                ->whereColumn('enrolleds.user_id', 'users.id')
                ->where('course_id', $this->selectedSubject)
                ->where('year', $this->selectedYear)
                ->where('term_id', $this->term);
        })
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('username', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%');
            });
        })
        ->orderBy('id', 'asc')
        ->paginate(50);
    }

    private function getEnrolledStudents()
    {
    
        return Enrolled::with('user')
                ->where('course_id', $this->selectedSubject)
                ->where('year', $this->selectedYear)
                ->where('term_id', $this->term)
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('adm_no', 'like', '%' . $this->search . '%')
                          ->orWhere('name', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('user_id', 'ASC')
                ->paginate(50);
    }

}
