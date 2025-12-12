<?php

namespace App\Models;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentRecord extends Eloquent
{
    use HasFactory;

    protected $fillable = [
        'session', 'user_id', 'my_class_id', 'section_id', 'my_parent', 'dorm_id', 'dorm_room_no', 'adm_no', 'year_admitted', 'wd', 'wd_date', 'grad', 'status','level','college_id','department_id','major','minor'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function my_parent()
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

    public function dorm()
    {
        return $this->belongsTo(Dorm::class);
    }

    public function getCollegeAttribute()
    {
        return $this->department?->college;
    }

    public function getDepartmentAttribute()
    {
        return $this->my_class?->class_type;
    }

    public function enrollments()
    {
        return $this->hasMany(Enrolled::class, 'student_id', 'user_id');
    }

    public function real_department()
    {
        return $this->belongsTo(ClassType::class,'department_id','id');
    }

    public function college()
    {
        return $this->belongsTo(college::class);
    }



}
