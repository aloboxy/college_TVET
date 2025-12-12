<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FailedCheck extends Command
{
    protected $signature = 'failed:check {exam_id=8}';
    protected $description = 'Update exam_records with failed = 1 if any of the student\'s marks is F';

    public function handle()
    {
        $examId = $this->argument('exam_id');

        DB::beginTransaction();

        try {
            // Get all students who took this exam
            $studentIds = DB::table('marks')
                ->where('exam_id', $examId)
                ->pluck('student_id')
                ->unique();

            foreach ($studentIds as $studentId) {
                // Get all marks for this student and exam
                $studentMarks = DB::table('marks')
                    ->where('exam_id', $examId)
                    ->where('student_id', $studentId)
                    ->pluck('grade_get');

                // Check if any grade is non-numeric or less than 2.0 (F)
                $hasFailed = false;
                foreach ($studentMarks as $grade) {
                    if (is_numeric($grade) && $grade < 2.0) {
                        $hasFailed = true;
                        break;
                    }
                }

                DB::table('exam_records')
                    ->where('exam_id', $examId)
                    ->where('student_id', $studentId)
                    ->update(['failed' => $hasFailed ? 1 : 0]);
            }

            DB::commit();
            $this->info("Failed status updated for exam ID $examId.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
        }
    }
}
