<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicYearTable extends Migration
{
    public function up()
    {
        Schema::create('academic_year', function (Blueprint $table) {
            $table->id();
            $table->string('year'); // no timestamps in schema dump
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_year');
    }
}
