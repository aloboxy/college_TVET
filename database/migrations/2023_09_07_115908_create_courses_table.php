<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('subject_id');
            $table->boolean('for_all')->default(0);
            $table->unsignedInteger('section_id')->nullable();
            $table->string('my_class_id')->nullable();
            $table->string('time_to');
            $table->string('time_from');
            $table->string('day');
            $table->string('session');
            $table->string('term_id');
            $table->unsignedInteger('teacher_id');
            $table->timestamps();
            $table->string('room')->nullable();
            $table->string('capacity')->default('50');
            $table->integer('total')->default(0);
            $table->string('year')->nullable();
            $table->string('department_id')->nullable();
            $table->string('level')->nullable();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreign('subject_id')->references('id')->on('subjects');
            // $table->foreign('my_class_id')->references('id')->on('my_classes'); // Type mismatch in DB schema (string vs int id), commenting out strict FK if types don't match, or we assume Laravel handles it loosely but usually not. Schema dump has key `courses_my_class_id_foreign` on `my_class_id`(250). It's a text prefix index likely, not a strict numeric FK.
            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('teacher_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
