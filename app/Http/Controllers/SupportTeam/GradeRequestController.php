<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\GradeChangeRequest;
use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradeRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $requests = GradeChangeRequest::query();

        $requests->where(function($q) use ($user) {
            $q->where('requested_by', $user->id);

            if ($user->can('mark.approve_dept')) {
                $q->orWhere('status', 'pending_dept');
            }

            if ($user->can('mark.approve_college')) {
                $q->orWhere('status', 'pending_college')
                  ->orWhere('status', 'approved');
            }
        });

        $d['requests'] = $requests->with(['student', 'subject', 'my_class', 'section', 'requester'])->orderBy('created_at', 'desc')->get();

        return view('pages.support_team.marks.grade_requests.index', $d);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_id' => 'required|exists:exams,id',
            'my_class_id' => 'required|exists:my_classes,id',
            'section_id' => 'required|exists:sections,id',
            'mark_id' => 'nullable|exists:marks,id',
            't1' => 'nullable|integer',
            't2' => 'nullable|integer',
            't3' => 'nullable|integer',
            't4' => 'nullable|integer',
            'tca' => 'nullable|integer',
            'exm' => 'nullable|integer', // exam score
        ]);

        // prepare json data
        $grade_data = array_filter($request->only(['t1', 't2', 't3', 't4', 'tca', 'exm']), function($v) { return !is_null($v); });

        // Capture original data
        $existing_mark = Mark::where([
            'student_id' => $data['student_id'],
            'subject_id' => $data['subject_id'],
            'exam_id' => $data['exam_id'],
            'year' => Qs::getSetting('current_session'),
        ])->first();

        $original_data = $existing_mark ? $existing_mark->only(['t1', 't2', 't3', 't4', 'tca', 'exm']) : [];


        GradeChangeRequest::create([
            'student_id' => $data['student_id'],
            'subject_id' => $data['subject_id'],
            'exam_id' => $data['exam_id'],
            'my_class_id' => $data['my_class_id'],
            'section_id' => $data['section_id'],
            'mark_id' => $data['mark_id'] ?? null,
            'requested_by' => Auth::id(),
            'data' => $grade_data,
            'original_data' => $original_data,
            'status' => 'pending_dept',
        ]);

        return back()->with('flash_success', 'Grade change request submitted successfully. It is pending Department Head approval.');
    }

    public function revert($id)
    {
        if (!Auth::user()->can('mark.approve_college')) {
            return back()->with('flash_danger', 'Unauthorized');
        }

        $req = GradeChangeRequest::findOrFail($id);
        
        if ($req->status !== 'approved') {
             return back()->with('flash_danger', 'Only approved requests can be reverted.');
        }

        DB::transaction(function() use ($req) {
            if ($req->original_data) {
                $mark = Mark::where([
                    'student_id' => $req->student_id,
                    'subject_id' => $req->subject_id,
                    'exam_id' => $req->exam_id,
                    'year' => Qs::getSetting('current_session'),
                ])->first();

                if($mark) {
                     $mark->update($req->original_data);
                }
            }
            $req->update(['status' => 'reverted']);
        });

        return back()->with('flash_success', 'Changes reverted. Grades restored to original state.');
    }

    public function approveDept($id)
    {
        if (!Auth::user()->can('mark.approve_dept')) {
            return back()->with('flash_danger', 'Unauthorized');
        }

        $req = GradeChangeRequest::findOrFail($id);
        if ($req->status !== 'pending_dept') {
             return back()->with('flash_danger', 'Request is not pending department approval.');
        }

        $req->update([
            'status' => 'pending_college',
            'dept_head_status' => 'approved'
        ]);

        return back()->with('flash_success', 'Approved by Department Head. Pending College Head approval.');
    }

    public function approveCollege($id)
    {
        if (!Auth::user()->can('mark.approve_college')) {
            return back()->with('flash_danger', 'Unauthorized');
        }

        $req = GradeChangeRequest::findOrFail($id);
             if ($req->status !== 'pending_college') {
             return back()->with('flash_danger', 'Request is not pending college approval.');
        }

        DB::transaction(function() use ($req) {
            $req->update([
                'status' => 'approved',
                'college_head_status' => 'approved'
            ]);
            $this->applyChange($req);
        });

        return back()->with('flash_success', 'Approved by College Head. Marks have been updated.');
    }

    public function reject($id)
    {
         if (!Auth::user()->can('mark.approve_dept') && !Auth::user()->can('mark.approve_college')) {
            return back()->with('flash_danger', 'Unauthorized');
        }
        
        $req = GradeChangeRequest::findOrFail($id);
        $req->update(['status' => 'rejected']);
        
        return back()->with('flash_success', 'Request rejected.');
    }

    protected function applyChange(GradeChangeRequest $req)
    {
        $data = $req->data;
        
        $mark = Mark::updateOrCreate(
            [
                'student_id' => $req->student_id,
                'subject_id' => $req->subject_id,
                'exam_id' => $req->exam_id,
                'year' => Qs::getSetting('current_session'), // Assuming current session
            ],
            [
                'my_class_id' => $req->my_class_id,
                'section_id' => $req->section_id,
            ]
        );

        // Update specific fields present in data
        foreach($data as $key => $val) {
            $mark->$key = $val;
        }
        
        $mark->save();
        // Recalculate totals/grades if necessary - simpler to trust helper or separate logic, 
        // but explicit save is safer. Ideally call a service to recalculate CUM/Grade.
    }
}
