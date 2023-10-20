<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected array $seeders = [
//        StatusesSeeder::class,
//        TypesSeeder::class,
//        DescriptionSeeder::class,
        PermissionsSeeder::class,
    ];
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->seeders as $seeder) {
            (new $seeder)->run();
        }
    }
}
