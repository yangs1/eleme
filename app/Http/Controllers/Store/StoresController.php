<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoresRequest;
use App\Repository\StoreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        app(StoresRequest::class )->scene('update')->validate( $request );

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


/*avatar
highlights
item_ratings
[{food_id: 508807792, food_name: "超级至尊比萨-铁盘", _id: "5a22f885ec81ce77ee8449b9", is_valid: 1,…},…]
rated_at "2017-02-10"
rating_star:5
rating_text:""
tags:[]
time_spent_desc:"按时送达"
username:"4*******b"
_id:"5a22f885ec81ce77ee8449b7"*/


/*
_id	5a24b9683ba8590522fa70bf
tips	809评价 月售545份
item_id	6
category_id	2
restaurant_id	1
activity	{…}
image_path	160211a2a2693.jpg
name	德州扒鸡
__v	0
specfoods	[…]
satisfy_rate	56
satisfy_count	323
attributes	[…]
0	{…}
icon_color	5ec452
icon_name	新
is_essential	false
server_utc	2017-12-03T12:37:27.907Z
specifications	[]
rating_count	809
month_sales	545
description	地方小吃
attrs	[]
display_times	[]
pinyin_name
is_featured	0
rating	4.5*/