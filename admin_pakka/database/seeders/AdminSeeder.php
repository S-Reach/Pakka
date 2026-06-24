<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'firstname' => 'Sosethika',
                'lastname' => 'Kou',
                'email' => 'sosethika@gmail.com',
                'password' => Hash::make('12345678'),
                'status' => '1',
                'photo' => null,
            ],
        ];

        foreach ($admins as $admin) {
            Admin::updateOrCreate(
                ['email' => $admin['email']], // Search by email
                $admin                         // Update or insert the rest
            );
        }
    }
}