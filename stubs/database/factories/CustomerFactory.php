<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->randomElement([fake()->firstName(), null]),
            'last_name' => fake()->lastName(),
            'customer_code' => fake()->unique()->bothify('??????##'),
            'enable_notification' => fake()->boolean(),
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'gender' => fake()->randomElement(['M', 'F', 'Other']),
            // It will be overridden based on "gender's" attribute value in configure() hook below
            'gender_other' => null,
            'note' => fake()->text(255),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Customer $customer) {
            if ($customer->gender === 'Other') {
                $customer->gender_other = fake()->word();
            }
        });
    }
}
