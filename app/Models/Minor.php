<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Qs;

class Minor extends Model
{
    use HasFactory;

    protected $fillable = [
        'major_id',
        'minor',
    ];

    public function students()
    {
        return $this->hasMany(StudentRecord::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class,'major_id');
    }

    public function department()
    {
        return $this->belongsTo(ClassType::class,'department_id');
    }


}

