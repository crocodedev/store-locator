<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_to_user', function (Blueprint $table) {
            $table->id();
            $table->integer('plan_id');
            $table->string('shop')->unique();
            $table->string('charge_id');
            $table->string('status');
            $table->string('active_until');// Время до которого действует подписка
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
        Schema::dropIfExists('plan_to_user');
    }
}
