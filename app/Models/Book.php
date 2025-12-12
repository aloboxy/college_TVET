<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //
    protected $fillable = [
        'name', 'author', 'description', 'book_type', 'url', 'location', 'total_copies', 'issued_copies', 'cover_image'
    ];
}
