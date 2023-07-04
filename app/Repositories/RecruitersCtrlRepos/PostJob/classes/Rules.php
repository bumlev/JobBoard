<?php
namespace App\Repositories\RecruitersCtrlRepos\PostJob\Classes;

class Rules 
{
    static function execute():array
    {
        return [
            "title" => "Required|Min:5",
            "content" => "Required",
            "skills" => "Required|Array",
            "skills.*" => "Required|numeric|not_in:0",
            "countries" => "Required|array",
            "countries.*" => "Required|numeric|not_in:0"
        ];
    }
}