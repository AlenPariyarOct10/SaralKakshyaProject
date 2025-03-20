<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSetting::create([
            'name'=>"Saral Kakshya",
            'description'=>"Smart Classroom Management System",
            'logo'=>'assets/images/logo-square.png',
            'favicon'=>'assets/images/logo.png',
        ]);
    }
}
