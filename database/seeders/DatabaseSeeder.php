<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test Name',
            'email' => 'test@email.com',
            'password' => 'test'
        ]);

        Log::info('User Api Key: ' . $user->createToken('client')->plainTextToken);
    }
}
