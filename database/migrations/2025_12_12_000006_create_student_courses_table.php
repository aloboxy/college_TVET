<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('student_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->timestamps();
            
            // Adding indexes as per schema dump (though not strict FKs defined in create statement usually, but good to have)
             $table->index('course_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_courses');
    }
}
