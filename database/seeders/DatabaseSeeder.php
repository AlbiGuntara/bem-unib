<?php

namespace Database\Seeders;

use App\Models\Admin\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Albi Guntara',
            'email' => 'albigoentara@gmail.com',
            'username' => 'alby',
            'password' => bcrypt('alby1234'),
        ]);
    }
}
