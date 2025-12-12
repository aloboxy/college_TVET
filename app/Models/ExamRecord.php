<?php

namespace App\Models;

use App\User;
use Eloquent;

class ExamRecord extends Eloquent
{
    protected $fillable = ['exam_id', 'my_class_id', 'student_id', 'section_id', 'af', 'af_id', 'ps', 'ps_id','t_comment', 'p_comment', 'year', 'total', 'ave', 'class_ave', 'pos', 'failed','department_id'];


    public function user()
{
   return  $this->belongsTo(User::class, 'student_id');
}
}


