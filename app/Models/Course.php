<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';

    protected $fillable = ['session', 'department_id','my_class_id', 'teacher_id', 'section_id', 'another_section','term_id', 'subject_id', 'time_from', 'time_to', 'day', 'room','total', 'capacity', 'for_all', 'year','level'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

     public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function enrolled()
    {
        return $this->hasMany(Enrolled::class);
    }

    public function department()
    {
        return $this->belongsTo(ClassType::class,'department_id');
    }
}
