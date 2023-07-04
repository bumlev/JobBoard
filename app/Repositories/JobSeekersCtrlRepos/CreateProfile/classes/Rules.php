<?php
namespace App\Repositories\JobSeekersCtrlRepos\CreateProfile\Classes;

class Rules
{
    static function execute():array
    {
        return [
            "education" => "Required|min:6",
            "degree_id" => "Required|numeric|not_in:0",
            "cv" => 'Required|mimes:jpeg,png,pdf,docx|max:2048',
            "cover_letter" => 'Required|mimes:jpeg,png,pdf,docx|max:2048',
            "phone" => "Required",
            "user_id" => "Required|unique:profiles,user_id",
            "country_id" => "Required|numeric|not_in:0",
            "skills" => "Required|array",
            "skills.*" => "Required|numeric|not_in:0"
        ];
    }
}