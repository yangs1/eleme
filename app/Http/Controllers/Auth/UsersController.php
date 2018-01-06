<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class UsersController extends Controller
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

        $this->validate($request,[
            'account' => 'required|unique:users,account',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'account'=>$request->input('account'),
            'password'=> bcrypt($request->input('password')),
            'name'=>Str::random(8)
        ]);

        $token = $user ? Auth::login($user) : '';

        return $this->response( '注册成功', ['token'=>$token],200 );
    }
}


