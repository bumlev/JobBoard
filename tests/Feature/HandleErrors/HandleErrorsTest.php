<?php
namespace Tests\Feature\HandleErrors;

use App\Repositories\HandleError\ErrorsNotMatchKeys;
use App\Repositories\HandleError\ErrorsNotNumberKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class HandleErrorsTest extends TestCase
{

    /** @test */
    public function errorsNotMatchKeys()
    {
        $request = new Request([
            "email" => "bumwe",
            "password" =>"levy"
        ]);
        $data = [
            "email"  => "bumwe",
        ];
        $data_rules = [
            "email"  => "Required",
        ];
        $validator = Validator::make($data , $data_rules);
        ErrorsNotMatchKeys::add($request , $data , $validator);
        $response = $validator->errors()->getFormat();
        $this->assertEquals($response , ":message");
    }

    /** @test */
    public function errorsNotNumberKeys()
    {
        $data = [
            "roles"  => ["shhs"=>1,2,3]
        ];
        $data_rules = [
            "roles"  => "Required",
            "roles.*"=> "Required"
        ];
        $validator = Validator::make($data , $data_rules);
        ErrorsNotNumberKeys::add($data , $validator);
        $response = $validator->errors()->getFormat();
        $this->assertEquals($response , ":message");
    }

    /** @test */
    public function errorsNotNumberKeys_not_array_data()
    {
        $data = [
            "roles"  => 1
        ];
        $data_rules = [
            "roles"  => "Required"
        ];
        $validator = Validator::make($data , $data_rules);
        ErrorsNotNumberKeys::add($data["roles"] , $validator);
        $this->assertTrue($validator->errors()->isEmpty());
    }
}