<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserPreferenceModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPreferenceModel>
 */
class UserPreferenceModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = UserPreferenceModel::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'categories' => $this->faker->randomElements(['Technology', 'Business', 'Sports', 'Health'], rand(1, 3)),
            'sources' => $this->faker->randomElements(['BBC', 'CNN', 'ESPN', 'Forbes'], rand(1, 2)),
            'authors' => $this->faker->randomElements(['John Doe', 'Jane Smith', 'Alice Brown', 'Bob Johnson'], rand(1, 2)),
        ];
    }
}
