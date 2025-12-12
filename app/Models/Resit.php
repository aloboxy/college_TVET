<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resit extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'subject_id','my_class_id','section_id','exam_id','year','grade_id','grade_letter','grade_point','created_at', 'updated_at'];
}
