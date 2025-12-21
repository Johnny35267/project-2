<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'role' => 'admin',
            'birthdate' => null,
            'profile_photo' => null,
            'id_photo' => null,
            'is_approved' => true,
            'is_active' => true,
            'mobile' => '0123456789',
            'address' => null,
            'card_type' => null,
            'card_number' => null,
            'security_code' => null,
            'expiry_date' => null,
            'account' => 0,
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // غير كلمة المرور حسب الحاجة
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
