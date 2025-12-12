<?php

namespace App\Http\Livewire;

use App\Models\Enrolled;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
use App\Helpers\Qs;
use Livewire\Component;

class Planned extends Component
{
    public $user;
    public $courses = [];
    protected $listeners = ['refreshpage' => '$refresh'];


    public function render()
    {
        // $nowtdate = Carbon::now();
        // $meat = $nowtdate->subMonth(1);
        // dd($meat);
        $year = Qs::getSetting('current_session');
        $sem = Qs::getSetting('Semester');
        // dd($meat);
        $this->user = Auth::user()->id;
        // $check = DB::table('student_records')->find($this->user);
        $this->courses = Enrolled::where('user_id', $this->user)->where('year', $year)->where('term_id', $sem)->get();
        // dd($this->courses);
        return view('livewire.planned');
    }

    public function delete($id)
    {
        $enrollment = Enrolled::find($id);
    
        if (!$enrollment) {
            $this->dispatchBrowserEvent('course-flash', [
                'message' => 'Course not found',
                'type' => 'error'
            ]);
            return;
        }
    
        $course = Course::find($enrollment->course_id);
    
        $enrollment->delete();
        
        if ($course) {
            $course->decrement('total',1);
        }
    
        $this->dispatchBrowserEvent('course-flash', [
            'message' => 'Course Dropped',
            'type' => 'success'
        ]);
    }
    
}