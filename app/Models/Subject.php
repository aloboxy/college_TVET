<?php

namespace App\Models;

use App\User;
use App\Models\Mark;
use Eloquent;

class Subject extends Eloquent
{
    protected $fillable = ['name', 'department_id', 'teacher_id', 'slug', 'term_id', 'clinical', 'credit', 'level','prerequisite_id'];

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function mark()
    {
        return $this->hasMany(Mark::class);
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }

    public function department()
    {
        return $this->belongsTo(ClassType::class,'department_id');
    }

    public function prerequisite()
    {
        return $this->belongsTo(Subject::class, 'prerequisite_id');
    }


    public function dependents()
    {
        return $this->hasMany(Subject::class, 'prerequisite_id');
    }


}
