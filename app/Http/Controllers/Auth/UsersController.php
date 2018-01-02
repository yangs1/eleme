<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseControllers;
use App\User;

use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class UsersController extends BaseControllers
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    */
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
 //return Auth::parseJWT("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiIxMjcuMC4wLjEiLCJzdWIiOjEsImlhdCI6MTUxNDg2MzEwMiwiZXhwIjoxNTE0ODY2NzAyLCJpZCI6MSwibmFtZSI6IjExMTExIiwic2lnbmF0dXJlIjoiMzMzMzMiLCJhdmF0YXIiOiIiLCJnZW5kZXIiOiJ1bnNlbGVjdGVkIn0=.NDUxMWJlMzc5YWYxZjcxZjg5Nzk0NzUxNmNhNDllY2ViOWNlNDhkNw==");

      //  var_dump(Auth::login(User::find(1)));


        //return unserialize(null);
       // return gettype(User::find(1)->only(['id']));
    }

    public function register(Request $request){

        /*$validator = $this->getValidationFactory()->make($request->all(),[
            'account' => 'required|users:account',
            'password' => 'required|max:6'
        ]);
        if ($validator->fails()){
            return $validator->errors()->first();
        }*/
        $this->validate($request,[
            'account' => 'required|users:account',
            'password' => 'required|max:6'
        ]);
    }

}


