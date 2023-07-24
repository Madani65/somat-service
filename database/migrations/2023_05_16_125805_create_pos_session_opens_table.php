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
        Schema::create('pos_session_opens', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_session")
                ->constrained('pos_sessions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->bigInteger("id_entity");
            $table->bigInteger("id_account");
            $table->dateTime("start_time");
            $table->dateTime("end_time")->nullable();
            $table->float("cash_open")->default(0);
            $table->float("cash_close")->default(0)->nullable();
            $table->string("notes_open")->nullable();
            $table->string("notes_close")->nullable();
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
        Schema::dropIfExists('pos_session_opens');
    }
};
