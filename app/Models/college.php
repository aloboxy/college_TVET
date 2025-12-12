<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class college extends Model
{
    use HasFactory;

    protected $fillable = ['name','dean'];

    public function department()
    {
        return $this->hasMany(ClassType::class,'college_id');
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'dean');
    }
    public function students()
    {
        return $this->hasMany(StudentRecord::class, 'college_id');
    }
}
