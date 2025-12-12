<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CourseCheck extends Command
{
    protected $signature = 'course:check';
    protected $description = 'Update exam_records with failed = 1 if any of the student\'s marks is F';

    public function handle()
    {

        DB::beginTransaction();

        try {
            // Get all students who took this exam
            $studentIds = DB::table('marks')
                ->where('year','2025-2026')
                ->where('exam_id',9)
                ->where('term_id',2)
                ->where('subject_id',775)
                ->distinct()->pluck('student_id');

                

            foreach ($studentIds as $studentId) {
                $see = DB::table('enrolleds')
                ->where('user_id',$studentId)
                ->where('year','2025-2026')
                ->where('term_id',2)
                ->where('subject_id',148)
                ->first();
                // Get all marks for this student and exam
               
                if($see){
                    $count = DB::table('marks')
                    ->where('student_id', $studentId)
                    ->where('year','2025-2026')
                    ->where('exam_id',9)
                    ->where('term_id',2)
                    ->where('subject_id',775)
                    ->update(['subject_id' => $see->course_id]);

                $this->info("Updated $count marks for student $studentId");
                }
               
            }

            DB::commit();
            $this->info("Course corrected.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
        }
    }
}
