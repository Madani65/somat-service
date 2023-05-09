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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name", 50);
            $table->bigInteger("id_entity");
            $table->foreignId("id_category")->nullable()
                ->constrained('product_categories')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->tinyInteger("is_manufacture");
            $table->double("price")->default(0);
            $table->double("init_price")->default(0);
            $table->string("sku",50)->nullable();
            $table->string("uom", 5);
            $table->double("measure_conv");
            $table->text('documents')->nullable();
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
        Schema::dropIfExists('products');
    }
};
