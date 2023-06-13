<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

     protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            
        ];
    }
    public function arrayState()
    {
        return [
            [
                "name" => "Admin",
                "slug" => "Admin",
                "permissions" => ['users.index' => true]
            ],
            [
                "name" => "JobSeeker",
                "slug" => "JobSeeker",
                "permissions" => ['jobs.appliedJobs' => true]
            ],
            [
                "name" => "Recruiter",
                "slug" => "Recruiter",
                "permissions" => ['jobs.postJob' => true]
            ]
        ];
    }
}
