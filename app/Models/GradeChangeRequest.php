<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'mark_id',
        'student_id',
        'subject_id',
        'my_class_id',
        'section_id',
        'exam_id',
        'requested_by',
        'data',
        'status',
        'dept_head_status',
        'college_head_status',
        'comments',
        'original_data'
    ];

    protected $casts = [
        'data' => 'array',
        'original_data' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function mark()
    {
        return $this->belongsTo(Mark::class);
    }
}
