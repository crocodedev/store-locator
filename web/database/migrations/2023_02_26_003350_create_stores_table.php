<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('session_id')->references('id')->on('session');

            // Название магазина
            $table->string('name')->comment('Название магазина (номер магазина)');
            $table->string('slug')->comment('Уникальный идинтификатор магазина (для доступа по ссылке)');

            // Приоритетные данные магазина
            $table->string('address_1')
                ->comment('Строка адреса (обычно для указания улицы)');
            $table->string('address_2')
                ->nullable()
                ->comment('Вторая строка адреса (обычно для номера помещения, апартоментов, корпуса и т.п.)');
            $table->string('city')->comment('Город где расположен магазин');
            $table->string('postcode')->comment('Почтовый индекс магазина');
            $table->string('state')->comment('Район/Облость где расположен магазин');
            $table->string('country')->comment('Страна где расположен магазин');

            // Координаты магазина
            $table->string('latitude')->comment('координата по широте');
            $table->string('longitude')->comment('координата по долгате');

            // Связь и аккаунты магазина
            $table->string('phone');
            $table->string('fax');
            $table->string('site');
            $table->string('social_instagram');
            $table->string('social_twitter');
            $table->string('social_facebook');
            $table->string('social_tiktok');

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
        Schema::dropIfExists('stores');
    }
}
