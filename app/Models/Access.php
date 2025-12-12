<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use HasFactory;
    protected $table = 'access';
    protected $fillable=['student_id', 'access', 'term_id', 'section_id', 'my_class_id', 'year'];


    public function user()
    {
        return $this->belongsTo(User::class,'student_id');
    }

    public function student()
    {
       return $this->belongsTo(StudentRecord::class, 'student_id', 'user_id');
    }
}
