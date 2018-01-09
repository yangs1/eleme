<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Models\User;
use App\Repository\StoreRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function update( Request $request )
    {
        app(StoreRequest::class )->scene('update')->validate( $request );

        $store = Auth::store();

        if(!$request->has(['opening_time', 'closing_time']) && $store->status == -1){
            return $this->response('请填写店铺营业时间');
        }

        $this->repository->update(
            $request->only(['name','address','latitude','longitude','phone','float_minimum_order_amount',
            'float_max_delivery_distance','float_delivery_fee','store_avatar']),
            $store->id
        );
    }

    public function info( Request $request )
    {
       return $this->response( '获取成功', Auth::store());
    }
}


