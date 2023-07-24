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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_account");
            $table->bigInteger("id_entity");
            $table->string("nisn", 30)->nullable();
            $table->string("nis", 30)->nullable();
            $table->string("certificate_number", 50)->nullable();
            $table->string("skhun", 50)->nullable();
            $table->date("effective_start_date")->nullable();
            $table->date("effective_end_date")->nullable();
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
        Schema::dropIfExists('students');
    }
};
