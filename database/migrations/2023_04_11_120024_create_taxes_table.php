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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string("name", 20);
            $table->bigInteger("id_entity");
            $table->tinyInteger("type")->default(1)->comment("1=Goods, 2=Services");
            $table->double("value")->default(0);
            $table->tinyInteger("is_autoadd")->nullable()->comment("1=Yes");
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
        Schema::dropIfExists('taxes');
    }
};
