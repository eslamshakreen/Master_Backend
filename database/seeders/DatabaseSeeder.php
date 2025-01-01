<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'اسلام شاكرين',
            'email' => 'eslamshakreen@gmail.com',
            'password' => bcrypt('1234567890'),
            'role' => 'admin',
            'phone' => '0918443317',
            'age' => '22',
            'gender' => 'male',
            'address' => 'السراج',
        ]);
    }
}
