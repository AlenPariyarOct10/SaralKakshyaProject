<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Psy\Util\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {$admissionDate = $this->faker->dateTimeBetween('-4 years', 'now');

        return [
            'fname' => $this->faker->firstName,
            'lname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '98' . $this->faker->numerify('########'),
            'address' => $this->faker->address,
            'gender' => $this->faker->randomElement(['male', 'female',]),
            'dob' => $this->faker->date(),
            'guardian_name' => $this->faker->name,
            'guardian_phone' => '98' . $this->faker->numerify('########'),
            'roll_number' => $this->faker->unique()->numerify('RN###'),
            'batch' => $admissionDate->format('Y'),
            'section' => $this->faker->randomElement(['A', 'B', 'C']),
            'admission_date' => $admissionDate->format('Y-m-d'),
            'profile_picture' => null,
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // or bcrypt('password')
            'status' => $this->faker->randomElement([0,1]),
            'remember_token' => \Illuminate\Support\Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'institute_id' => $this->faker->randomElement([1, 2, 5]),
        ];
    }
}
