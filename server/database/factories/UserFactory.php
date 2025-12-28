<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone_number' => fake()->randomNumber(8),
            'date_of_birth' => fake()->dateTime(),
            'address' => fake()->sentence(4),
            'civil_status' => fake()->randomElement(['single', 'married']),
            'gender' => fake()->randomElement(['male', 'female']),
            'id_card_number' => fake()->randomNumber(8),
            'nationality' => fake()->randomElement(['American', 'Canadian', 'British', 'French', 'German', 'Spanish', 'Italian', 'Australian']),
            'university' => fake()->randomElement(['MIT', 'Stanford', 'Harvard', 'Berkeley', 'Oxford', 'Cambridge', 'NYU', 'UCLA']),
            'history' => fake()->randomElement(['Software Engineer with startup experience', 'Designer from agency background', 'Marketing specialist with B2B focus', 'Data analyst from finance sector', 'Project manager with agile expertise']),
            'experience_level' => fake()->numberBetween(1, 15),
            'source' => fake()->randomElement(['recruitment', 'referral', 'internal', 'headhunting', 'career_fair']),
            'position' => fake()->randomElement(['Software Engineer', 'UX Designer', 'Marketing Manager', 'Data Analyst', 'Project Manager', 'DevOps Engineer', 'Product Manager']),
            'grade' => fake()->randomElement(['Junior', 'Mid-level', 'Senior', 'Lead', 'Principal']),
            'hiring_date' => now(),
            'contract_end_date' => fake()->dateTime(mktime(date('Y') + 20)),
            'allowed_leave_days' => random_int(10, 30),
            'department_id' => rand(1, 5),
            'image_path' => 'storage/images/default-avatar.svg',
        ];
    }
}
