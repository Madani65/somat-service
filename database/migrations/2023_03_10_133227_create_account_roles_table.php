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
        Schema::create('account_roles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_entity");
            $table->bigInteger("id_account");
            $table->foreignId("id_role")->nullable()
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();
            $table->index(['id_entity', 'id_account'], "roles_idx1");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_roles');
    }
};
