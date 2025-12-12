<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_change_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('mark_id')->nullable();
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('my_class_id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('exam_id');
            $table->unsignedInteger('requested_by');
            $table->json('data'); // Stores component scores e.g. {'t1': 10, 'exm': 50}
            $table->string('status')->default('pending_dept'); // pending_dept, pending_college, approved, rejected
            $table->string('dept_head_status')->nullable();
            $table->string('college_head_status')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            // Foreign keys would be ideal but relying on matching types for now
            // $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grade_change_requests');
    }
}
