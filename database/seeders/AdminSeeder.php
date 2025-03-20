<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::factory()->create([
            'fname' => 'Alen',
            'lname' => 'Pariyar',
            'email' => 'oct10.alenpariyar@gmail.com',
            'phone' => '9816699413',
            'address' => 'Lamjung',
            'profile_picture' => 'assets/uploads/alen-photo.jpg',
            'password' => Hash::make('9816699413@#Alen'),
        ]);
    }
}
