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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_session")->nullable()
                ->constrained('pos_sessions')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId("id_session_open")->nullable()
                ->constrained('pos_session_opens')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId("id_business_partner")->nullable()
                ->constrained('business_partners')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->bigInteger("id_entity");
            $table->bigInteger("id_account");
            $table->bigInteger("id_pos_table")->nullable();
            $table->string("receipt_num", 30);
            $table->float("amount");
            $table->float("tax");
            $table->float("discount");
            $table->float("charge");
            $table->foreignId("id_return")
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->tinyInteger("paid_status")->comment("0 = Unpaid, 1 = Paid");
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
        Schema::dropIfExists('orders');
    }
};
