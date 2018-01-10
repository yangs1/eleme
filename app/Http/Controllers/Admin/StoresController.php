<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoresRequest;
use App\Models\Store;
use App\Models\User;
use App\Repository\StoreRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StoresController extends Controller
{
    protected $repository;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    */
    public function __construct( StoreRepository $repository )
    {
        $this->repository = $repository;
    }

    public function create( Request $request )
    {
        app(StoresRequest::class )->scene('create')->validate( $request );

        $user_id = $request->input('user_id');
        if ( $store = Store::where('user_id', $user_id)->first() ){
            throw new BadRequestHttpException( "该用户已拥有店铺" );
        }

        $this->repository->create(
            $request->only(['name','address','latitude','longitude','phone',
                'category_original','category_sub','store_avatar','user_id']),
            $user_id
        );
    }


    public function lists( Request $request )
    {
        $page = $request->input('page',1);
        $perPage = $request->input('perPage', 10);

        $this->repository->lists( $page, $perPage );
    }


    public function storeLock( Request $request )
    {
        $this->validate($request,[
            'store_id' => 'required',
        ]);

        Store::where('id', $request->input('store_id'))->update(['status'=>-2]);

        return $this->response('操作成功');

        //var_dump($user->created_at);

        //var_dump( Carbon::createFromFormat('U', time()) );
        //User::where('id', $request->input('user_id', 0))->update([])
    }

    public function storeUnLock( Request $request )
    {
        $this->validate($request,[
            'store_id' => 'required',
        ]);

        Store::where('id', $request->input('store_id'))->update(['status'=>1]);

        return $this->response('操作成功');
    }
}


