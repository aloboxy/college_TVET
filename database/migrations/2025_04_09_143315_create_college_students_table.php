<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegeStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->reference('id')->on('users');
            $table->integer('adm_no');
            $table->integer('college')->reference('id')->on('college');
            $table->integer('department')->reference('id')->on('class_types');
            $table->string('level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('college_students');
    }
}
