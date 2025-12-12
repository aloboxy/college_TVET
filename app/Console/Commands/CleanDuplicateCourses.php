<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanDuplicateCourses extends Command
{
   protected $signature = 'enrollments:clean {year=2025-2026} {exam_id=9} {subject_id=1}';
    protected $description = 'Remove duplicate subject enrollments and invalid marks safely.';

    public function handle()
    {
        $year = $this->argument('year');
        $exam = $this->argument('exam_id');
        $subject_id = $this->argument('subject_id');

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

                        $this->info("Deleted $deletedCount duplicates for student {$student->user_id} (subject_id {$student->subject_id})");
        }
    }
}

        $this->info("✅ Done. Total deleted marks: $totalDeleted");
            
            DB::commit();
            $this->info("✅ Cleanup complete for $year - Exam $exam.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error: " . $e->getMessage());
        }
    }
}
