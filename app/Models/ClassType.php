<?php

namespace App\Models;
use App\User;

use Eloquent;

class ClassType extends Eloquent
{
    protected$fillable =['name', 'code','program','teacher_id','total_credit','college_id','class_base'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function college()
    {
        return $this->belongsTo(college::class, 'college_id');
    }
    public function major()
    {
        return $this->hasMany(Major::class);
    }
}
