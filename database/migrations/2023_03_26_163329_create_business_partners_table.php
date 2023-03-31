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
        Schema::create('business_partners', function (Blueprint $table) {
            $table->id();
            $table->string("partner_num", 70)->nullable();
            $table->string("partner_name", 70);
            $table->foreignId("id_partner_type")->nullable()
                ->constrained('partner_types')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->tinyInteger("as_customer")->nullable();
            $table->tinyInteger("as_supplier")->nullable();
            $table->bigInteger("id_entity");
            $table->string("email", 50)->nullable();
            $table->string("address", 240)->nullable();
            $table->char("gender", 1)->nullable()->comment("L=Laki-laki, P=Perempuan");
            $table->string("phone", 20)->nullable();
            $table->string("sales_person", 50)->nullable();
            $table->string("sales_phone", 20)->nullable();
            $table->date("date_of_birth")->nullable();
            $table->tinyInteger("as_default")->default(0);
            $table->text("documents")->nullable();
            $table->timestamps();
            $table->index(['partner_num', 'id_entity'], "business_partner_idx1");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_partners');
    }
};
