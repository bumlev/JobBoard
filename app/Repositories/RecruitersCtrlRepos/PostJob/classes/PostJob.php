<?php
namespace App\Repositories\RecruitersCtrlRepos\PostJob\Classes;

use App\Models\Job;
use Illuminate\Http\Request;

class PostJob
{
    static function execute(Request $request)
    {
        $data = self::ValidateData($request);
        if(gettype($data) == "object")
            return response()->json(["errorsValidation" => $data->errors()] , 422);

        $job = Job::create($data);
        $job->skills()->attach($data["skills"]);
        $job->countries()->attach($data["countries"]);
        return response()->json(["createjob" => $job] , 201);
    }

    // Validate data
    static private function ValidateData(Request $request)
    {
        return ValidatorData::execute($request);
    }
}
