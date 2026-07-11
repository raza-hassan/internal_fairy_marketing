<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // uncomment if you want to add record into database run command ==> php artisan db:seed
        $this->call(UsersTableSeeder::class);
    }
}
