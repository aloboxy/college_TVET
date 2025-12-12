<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddElibraryFieldsToBooksTable extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('books', 'author')) {
                $table->string('author')->nullable();
            }
            if (!Schema::hasColumn('books', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('books', 'book_type')) {
                $table->string('book_type')->default('physical'); // physical, digital
            }
            if (!Schema::hasColumn('books', 'url')) {
                $table->string('url')->nullable(); // For PDF path
            }
            if (!Schema::hasColumn('books', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('books', 'total_copies')) {
                $table->integer('total_copies')->default(0);
            }
            if (!Schema::hasColumn('books', 'issued_copies')) {
                $table->integer('issued_copies')->default(0);
            }
             if (!Schema::hasColumn('books', 'cover_image')) {
                $table->string('cover_image')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['name', 'author', 'description', 'book_type', 'url', 'location', 'total_copies', 'issued_copies', 'cover_image']);
        });
    }
}
