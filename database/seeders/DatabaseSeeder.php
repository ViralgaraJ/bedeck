<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            CategorySeeder::class,
            ServiceSeeder::class,
            PartnerSeeder::class,
            ProductSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
