<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessTable extends Migration
{
    public function up()
    {
        Schema::create('access', function (Blueprint $table) {
            $table->id();
            $table->integer('term_id')->nullable();
            $table->integer('section_id');
            $table->string('year')->nullable();
            $table->integer('my_class_id');
            $table->integer('student_id');
            $table->integer('access')->default(0)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('Updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('access');
    }
}
