<?php

namespace App\Models;
use App\User;
use App\Models\{StudentRecord, Course, Exam, MyClass, Subject};

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrolled extends Model
{
    use HasFactory;


    protected $fillable=['user_id', 'course_id', 'my_class_id', 'section_id','session','teacher_id','term_id', 'subject_id', 'time_from', 'time_to', 'day','room','year'];

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class,);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
