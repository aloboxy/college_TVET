<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\ClassType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CleanDupliController extends Controller
{
    //

    public function index()
    {
        $years = DB::table('academic_year')->get();
        $departments = ClassType::all();
        return view('pages.support_team.duplicate.index', compact('departments','years'));
    }

    public function clean(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required',
            'exam_id' => 'required',
            'subject_id' => 'required',
        ]);
        // dd($request->all());
        $year = $validated['year'];
        $exam = $validated['exam_id'];

        $subject_id = DB::table('courses')
            ->where('year', $year)
            ->where('id', $validated['subject_id'])
            ->first()->subject_id;

         DB::beginTransaction();
        try {
            // 1️⃣ Find duplicated subjects per student
           $get_students = DB::table('enrolleds')
                ->select('user_id', 'subject_id', 'course_id')
                ->where('year', $year)
                ->where('subject_id', $subject_id)
                ->get();

            $courses = DB::table('courses')
                ->select('id')
                ->where('year', $year)
                ->where('subject_id', $subject_id)
                ->pluck('id') // simpler array of course IDs
                ->toArray();

            $totalDeleted = 0;

            foreach ($get_students as $student) {

                // Find all mark records for this student that match one of those course IDs
                $marks = DB::table('marks')
                    ->where('student_id', $student->user_id)
                    ->whereIn('subject_id', $courses)
                    ->where('exam_id', $exam)
                    ->where('year', $year)
                    ->orderBy('id', 'asc')
                    ->get();

                // Only act if the student has more than one mark record for same subject
                if ($marks->count() > 1) {
                    // Keep the first valid mark (non-zero grade_get)
                    $validMark = $marks->first(function ($mark) {
                        return $mark->grade_get !== null && $mark->grade_get > 0;
                    });

                    // Get IDs of marks to delete
                    $marksToDelete = $marks->filter(function ($mark) use ($validMark) {
                        // Delete all with grade_get 0/null, OR any duplicate that's not the valid one
                        return $mark->grade_get == 0 
                            || $mark->grade_get === null 
                            || ($validMark && $mark->id !== $validMark->id);
                    })->pluck('id');

                    if ($marksToDelete->isNotEmpty()) {
                        DB::table('marks')
                            ->whereIn('id', $marksToDelete)
                            ->delete();

                        $deletedCount = $marksToDelete->count();
                        $totalDeleted += $deletedCount;

                       
                    }
                }
            }

        DB::commit();
        return Qs::clean_ok();
        } catch (\Exception $e) {
            DB::rollBack();
            return Qs::jsonError($e->getMessage());
        }
    }
}
