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
        Schema::create('entity_currencies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_entity")->index("entity_currencies_idx1");
            $table->char("currency_code", 3)->nullable();
            $table->foreign("currency_code")->references('code')
                ->on('currencies')
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
        Schema::dropIfExists('entity_currencies');
    }
};
