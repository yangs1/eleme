<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class RegisterController
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
    public function register(Request $request)
    {
        //app(UserRequest::class )->scene('B')->validate($request->all());
        //var_dump(session()->getId());
        //session()->put('fuck', session()->getId());
        //$user  = User::find(1);
       // var_dump($user->getAuthPassword());
        //Auth::loginUsingId($request->input('id'));
        return ['success'=>"10086"];
        //var_dump();
    }

    public function check()
    {
        var_dump(Auth::check());
        //
       // return session()->get('fuck');
    }
}
