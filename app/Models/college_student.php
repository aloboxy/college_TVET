<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class college_student extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','adm_no','college','department','level'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
