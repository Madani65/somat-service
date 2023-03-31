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
        Schema::create('account_entity_accesses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_entity_parent");
            $table->bigInteger("id_entity");
            $table->bigInteger("id_account");
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
        Schema::dropIfExists('account_entity_accesses');
    }
};
