<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SuperAdmin::create([
            'fname'=>"Alen",
            'lname'=>"Pariyar",
            'email'=>"oct10.alenpariyar@gmail.com",
            'password'=>Hash::make('9816699413@#Alen'),
            'profile_picture'=>'assets/uploads/alen-photo.jpg',

        ]);
    }
}
