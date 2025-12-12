<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    use HasFactory;

    protected $fillable = ['total_collect','amount_excepted','expenditure','grand_total','session','created_at', 'updated_at'];
}
