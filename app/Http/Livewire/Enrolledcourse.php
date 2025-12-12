<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Enrolled;
use App\Models\MyClass;
use App\Models\PaymentRecord;
use App\Models\StudentRecord;
use App\Models\ClassType;
use App\Helpers\Qs;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Enrolledcourse extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $courses = [];
    public $enrolled = [];
    public $user;
    public $all;
    public $classes = null;
    public $semesters;
    public $class;
    public $check = [];
    public $count;
    public $see;
    public $year;
    public $sem;
    public $level;
    public $paid;
    public $selectedLevel;
    public $department;
    public $selectedDepartment;
    public $coursall;
    public $selectedSubject;
    public $selectedClass = null;
    public $selectedSemester = null;
    public $userId;

    protected $listeners = ['refreshpage' => '$refresh'];

    public function mount()
    {
        $this->classes = MyClass::all();
        $this->year = Qs::getSetting('current_session');
        $this->sem = Qs::getSetting('Semester');
        $this->department = ClassType::all();
        $this->userId = Auth::id();
    }

    public function render(): \Illuminate\View\View
    {
        $this->paid = PaymentRecord::with('payment', 'student')
            ->where('student_id', $this->userId)
            ->whereHas('payment', function ($query) {
                $query->where('title', 'Registration')
                    ->where('year', $this->year)
                    ->where('term_id', $this->sem);
            })
            ->first()
            ->paid ?? 0;

        try {
            if (!$this->userId) {
                return $this->emptyView();
            }

            $studentRecord = StudentRecord::where('user_id', $this->userId)->first();
            if (!$studentRecord) {
                return $this->emptyView();
            }

            $myClass = MyClass::find($studentRecord->my_class_id);
            if (!$myClass) {
                return $this->emptyView();
            }

            if (!$this->selectedLevel || !$this->selectedDepartment) {
                return $this->emptyView(); // Avoid query if filters not selected
            }

            $subjects = Course::where('year', $this->year)
                ->where('term_id', $this->sem)
                ->where('level', $this->selectedLevel)
                ->where(function ($query) {
                    $query->whereRaw("FIND_IN_SET(?, department_id)", [$this->selectedDepartment]);
                })
                ->paginate(10);

            return view('livewire.enrolledcourse', ['subjects' => $subjects]);
        } catch (\Exception $e) {
            Log::error('Error in Enrolledcourse render: ' . $e->getMessage());
            return $this->emptyView();
        }
    }

    private function emptyView()
    {
        return view('livewire.enrolledcourse', [
            'subjects' => new LengthAwarePaginator([], 0, 10),
        ]);
    }

    public function updatedSelectedClass($value)
    {
        $this->selectedClass = $value;
    }

    public function updatedSelectedSemester($value)
    {
        $this->selectedSemester = $value;
    }

    public function updatedSelectedDepartment($value)
    {
        $this->selectedDepartment = $value;
    }

    public function updatedSelectedLevel($value)
    {
        $this->selectedLevel = $value;
    }

    public function store($id)
    {
        $userId = Auth::id();
        $studentRecord = StudentRecord::where('user_id', $userId)->first();

        if (!$studentRecord) {
            $this->dispatchBrowserEvent('course-flash', [
                'message' => 'Student record not found.',
                'type' => 'error'
            ]);
            return;
        }

        $course = Course::find($id);
        if (!$course) {
            $this->dispatchBrowserEvent('course-flash', [
                'message' => 'Something went wrong',
                'type' => 'error'
            ]);
            return;
        }

        $enrolledCount = Enrolled::where('course_id', $id)
            ->where('year', $this->year)
            ->count();

        $existingEnrollment = Enrolled::where('subject_id', $course->subject_id)
            ->where('user_id', $userId)
            ->first();

        if ($enrolledCount == 0) {
            $course->update(['total' => 1]);
        } elseif (!$existingEnrollment) {
            if ($enrolledCount >= $course->capacity) {
                $course->update(['total' => $enrolledCount]);
            } else {
                $course->update(['total' => ++$enrolledCount]);
            }
        }

        if ($enrolledCount >= $course->capacity) {
            $this->dispatchBrowserEvent('course-flash', [
                'message' => 'Session Full',
                'type' => 'error'
            ]);
            return;
        }

        $day = is_array($course->day) ? implode(',', $course->day) : $course->day;

        Enrolled::updateOrCreate(
            [
                'subject_id' => $course->subject_id,
                'user_id' => $userId,
            ],
            [
                'course_id' => $course->id,
                'teacher_id' => $course->teacher_id,
                'session' => $course->session,
                'term_id' => $course->term_id,
                'time_from' => $course->time_from,
                'time_to' => $course->time_to,
                'year' => $this->year,
                'day' => $day,
                'room' => $course->room,
            ]
        );

        $this->dispatchBrowserEvent('course-flash', [
            'message' => 'Course added Successfully',
            'type' => 'success'
        ]);
    }
}
