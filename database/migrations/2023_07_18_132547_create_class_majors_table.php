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
        Schema::create('class_majors', function (Blueprint $table) {
            $table->id();
            $table->string("code", 15)->nullable();
            $table->string("name", 20);
            $table->string("description", 240)->nullable();
            $table->foreignId("id_school_level")->nullable()
                ->constrained('school_levels')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->char("active_flag", 1);
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
        Schema::dropIfExists('class_majors');
    }
};
