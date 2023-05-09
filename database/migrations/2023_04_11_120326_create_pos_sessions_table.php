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
        Schema::create('pos_sessions', function (Blueprint $table) {
            $table->id();
            $table->string("name", 50);
            $table->bigInteger("id_entity");
            $table->bigInteger("id_entity_map");
            $table->tinyInteger("notes_flag")->default(0);
            $table->tinyInteger("tables_flag")->default(0);
            $table->tinyInteger("casier_print_flag")->default(0);
            $table->tinyInteger("kitchen_print_flag")->default(0);
            $table->tinyInteger("custom_receipt_flag")->default(0);
            $table->string("custom_receipt_header", 30)->nullable();
            $table->string("custom_receipt_footer", 30)->nullable();
            $table->tinyInteger("autoprint_before_flag")->default(0);
            $table->tinyInteger("autoprint_after_flag")->default(0);
            $table->text("documents")->nullable();
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
        Schema::dropIfExists('pos_sessions');
    }
};
