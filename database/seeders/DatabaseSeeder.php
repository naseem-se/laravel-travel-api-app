<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('users')->insert([
            [
                'full_name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'phone_number' => '03001234567',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
         
                'is_google' => false,
                'is_apple' => false,
                'code' => 123456,
                'is_verified' => false,
                'role' => 'agency',
            ],
            [
                'full_name' => 'Bob Williams',
                'email' => 'bob@example.com',
                'phone_number' => '03007654321',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                
                'is_google' => false,
                'is_apple' => false,
                'code' => 567890,
                'is_verified' => false,
                'role' => 'traveler',
            ],
            [
                'full_name' => 'Charlie Davis',
                'email' => 'charlie@example.com',
                'phone_number' => '03001112233',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
               
                'is_google' => false,
                'is_apple' => false,
                'code' => 432165,
                'is_verified' => false,
                'role' => 'local_guide',
            ],
        ]);

    


    }
}
