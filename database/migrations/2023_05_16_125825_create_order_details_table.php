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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_order")
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string("description", 70)->nullable();
            $table->foreignId("id_product")->nullable()
                ->constrained('products')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId("id_parent")->nullable()
                ->constrained('order_details')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->float("qty")->nullable();
            $table->float("price")->nullable();
            $table->float("amount")->nullable();
            $table->tinyInteger("type")->comment("1 = Item, 2 = Discount, 3 = Tax");
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
        Schema::dropIfExists('order_details');
    }
};
