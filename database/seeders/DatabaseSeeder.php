<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Justinas',
            'email' => 'bernatavicius.justinas@gmail.com',
            'password' => 'justinas'
        ]);

        $user->createToken('client');
    }
}
