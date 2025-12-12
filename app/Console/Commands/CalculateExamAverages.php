<?php

namespace App\Console\Commands;
use App\Models\ExamRecord;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\Grade;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

use Illuminate\Console\Command;

class CalculateExamAverages extends Command
{
    protected $signature = 'exam:calculate-averages {exam_id=8}';
    protected $description = 'Calculate and update average grades for a given exam';

    public function handle()
    {
        $examId = $this->argument('exam_id');

        DB::beginTransaction();

        try {
            $examRecords = Mark::where('exam_id', $examId)->get();

            foreach ($examRecords as $record) {
                $course = Course::find($record->subject_id);
                if (!$course) continue;

                $subject = Subject::find($course->subject_id);
                if (!$subject) continue;

                // Prefer tex1, fallback to tex2
                $value = null;
                if (is_numeric($record->tex1)) {
                    $value = $record->tex1;
                } elseif (is_numeric($record->tex2)) {
                    $value = $record->tex2;
                }

                if (!is_numeric($value)) continue;

                $grade = Grade::where('mark_from','<=', $value)
                              ->where('mark_to', '>=', $value)
                              ->first();

                if (!$grade) continue;

                $credit = $subject->credit;
                $get = $grade->grade * $credit;

                $credit_sum = Mark::join('courses', 'marks.subject_id', '=', 'courses.id')
                                    ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
                                    ->where('marks.exam_id', $examId)
                                    ->where('marks.student_id', $record->student_id)
                                    ->selectRaw('SUM(subjects.credit) as total_credit')
                                    ->first();

                $ct = DB::table('marks')
                            ->where('student_id', $record->student_id)
                            ->where('exam_id', $examId)
                            ->where(function ($query) {
                                $query->where('tex1', '>', 0)->orWhere('tex2', '>', 0);
                            })
                            ->sum('grade_get');

                $total_credit = $credit_sum->total_credit ?: 1; // avoid division by zero
                $average = $ct / $total_credit;



                ExamRecord::where('exam_id',$examId)->where('student_id',$record->student_id)->update([
                'total' => $ct,
                'ave' => $average,

            ]);

                $record->grade_get = $get;
                $record->save();
            }

            DB::commit();
            echo "Averages successfully calculated and updated for exam ID $examId.\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Failed to update averages: " . $e->getMessage() . "\n";
        }
    }


}
