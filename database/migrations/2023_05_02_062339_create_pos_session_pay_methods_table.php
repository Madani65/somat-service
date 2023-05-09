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
        Schema::create('pos_session_pay_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_session")
                ->constrained('pos_sessions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId("id_payment_method")
                ->constrained('payment_methods')
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
        Schema::dropIfExists('pos_session_pay_methods');
    }
};
