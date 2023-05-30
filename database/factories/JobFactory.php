<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
     /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $textOptions = ["Vue.js Developer", "React.js Developer", "Laravel Developer"];
        $title = $this->faker->randomElement($textOptions);
        return [
            "title" => $title,
            "content" => "We want a ".$title,
            "user_id" => 8
        ];
    }
}
