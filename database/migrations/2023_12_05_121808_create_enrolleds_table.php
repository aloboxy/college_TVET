<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrolledsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrolleds', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('my_class_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->unsignedInteger('user_id'); // Renamed from student_id
            $table->string('session');
            $table->string('term_id');
            $table->string('time_to');
            $table->string('time_from');
            $table->string('day');
            $table->timestamps();
            $table->string('room');
            $table->integer('teacher_id');
            $table->string('year')->nullable();
        });

        Schema::table('enrolleds', function (Blueprint $table) {
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('my_class_id')->references('id')->on('my_classes');
            $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('teacher_id') // schema dump doesn't show FK for teacher_id on enrolleds but does show key `enrolleds_user_id_foreign`
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrolleds');
    }
}
