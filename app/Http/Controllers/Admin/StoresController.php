<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreRequest;
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
        app(StoreRequest::class )->scene('create')->validate( $request );

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
}


