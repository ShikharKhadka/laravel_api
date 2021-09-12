<?php

namespace Database\Factories;

use App\Models\Sms;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sms::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'phone_number' => $faker->phone_number,
        ];
    }
}
