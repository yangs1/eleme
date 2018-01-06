<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Models\User;
use App\Repository\StoreRepository;
use App\Repository\UsersRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UsersController extends Controller
{
    protected $repository;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    */
    public function __construct( UsersRepository $repository )
    {
        $this->repository = $repository;
    }

    public function lists( Request $request ){
        $page = $request->input('page',1);
        $perPage = $request->input('perPage', 10);

        $this->repository->lists( $page, $perPage );
    }

    public function userLock( Request $request )
    {
        $this->validate($request,[
            'user_id' => 'required',
            'forever' => 'boolean',
            'time_count'  =>"required_unless:forever,true,1",
            'unit'  =>"required_unless:forever,true,1|in:hours,days,weeks,months,year"
        ]);

        if ($request->input('forever')){
            User::where('id', $request->input('user_id'))->update(['is_lock'=>2]);
        }else{
            $time_count = $request->input('time_count');
            $unit = $request->input('unit');

            User::where('id', $request->input('user_id'))
                ->update(['is_lock'=>1, 'unlock_time'=> Carbon::parse("+$time_count $unit")]);
        }

        return $this->response('操作成功');

       //var_dump($user->created_at);

        //var_dump( Carbon::createFromFormat('U', time()) );
        //User::where('id', $request->input('user_id', 0))->update([])
    }

    public function userUnLock( Request $request )
    {
        $this->validate($request,[
            'user_id' => 'required',
        ]);

        User::where('id', $request->input('user_id'))
            ->update(['is_lock'=>0]);

        return $this->response('操作成功');
    }
}


