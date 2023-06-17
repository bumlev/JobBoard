<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "education" => "UK University",
            "degree_id" => 4,
            "cv" => self::getFile(),
            "cover_letter" => self::getFile(),
            "phone" => "+250788910234",
            "country_id" => 186,
        ];
    }

    static private function getFile()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('example.png', 500);
        return $file;
    }
}
