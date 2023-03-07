<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('billing_plans')->insert([
            'name' => 'Подписка на месяц',
            'price' => 5,
            'interval' => 'EVERY_30_DAYS', // Ежемесячно
        ]);
        DB::table('billing_plans')->insert([
            'name' => 'Подписка на год',
            'price' => 10,
            'interval' => 'ANNUAL', // Ежегодно
        ]);
    }
}
