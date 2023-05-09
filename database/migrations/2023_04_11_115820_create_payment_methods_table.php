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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string("name", 20);
            $table->bigInteger("id_entity");
            $table->tinyInteger("type")->default(1)->comment("1=percentage, 2=fixed");
            $table->double("value")->default(0);
            $table->tinyInteger("is_default")->nullable()->comment("1=Yes");
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
        Schema::dropIfExists('payment_methods');
    }
};
