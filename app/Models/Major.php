<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Qs;

class Major extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'major',
    ];
    public function college()
    {
        return $this->belongsTo(College::class,'college_id');
    }
    public function students()
    {
        return $this->hasMany(StudentRecord::class);
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function department()
    {
        return $this->belongsTo(ClassType::class,'department_id');
    }

    public function minor()
    {
        return $this->hasMany(Minor::class,'id', 'major_id');
    }

}

