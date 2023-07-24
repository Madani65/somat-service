<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string("class_name", 50);
            $table->string("class_level", 10)->nullable();
            $table->foreignId("class_major_id")->nullable()
                ->constrained('class_majors')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId("school_year_id")->nullable()
                ->constrained('school_years')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->integer("school_id")->nullable();
            $table->integer("teacher_id")->nullable();
            $table->foreignId("class_category_id")->nullable()
                ->constrained('school_levels')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
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
        Schema::dropIfExists('classes');
    }
};
