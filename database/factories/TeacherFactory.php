<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Psy\Util\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fname' => $this->faker->firstName,
            'lname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '98' . $this->faker->numerify('########'),
            'address' => $this->faker->address,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'dob' => $this->faker->date('Y-m-d', '-25 years'),
            'qualification' => $this->faker->randomElement(['MCA', 'MSc CSIT', 'MEd', 'MPhil', 'PhD']),
            'subject' => $this->faker->randomElement(['Math', 'English', 'Science', 'Computer', 'Nepali']),
            'profile_picture' => null,
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // use bcrypt
            'status' => $this->faker->randomElement([1,0]),
            'remember_token' => \Illuminate\Support\Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
