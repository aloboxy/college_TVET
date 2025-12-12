<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionsTable extends Migration
{
    public function up()
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->string('voter_id');
            $table->string('v_pass');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elections');
    }
}
