<?php

namespace App\Http\Requests;

use Foundation\Concerns\FromRequests;

class StoreRequest extends FromRequests
{
    public function rules()
    {
        return [
            //'account' => 'required|string|max:20|unique:users',
        ];
    }

    public function sceneRules()
    {
        return [
            'create'=> [
                'name' => 'required|string|max:20',
                'address' => 'required|string|max:45',
                'latitude' => 'required',
                'longitude' => 'required',
                'phone' => 'required|min:11|max:20',
                'category_original' => 'required|integer',
                'category_sub' => 'required|integer',
                /*'float_minimum_order_amount'=>'required|integer',
                'float_max_delivery_distance'=>'required|integer',
                'float_delivery_fee'=>'required|integer',*/
                'store_avatar'=>'max:255',
                'user_id'=>'required',
                //'password'=>'min:6',//promotion_info
            ],
            'update' => [
                'name' => 'sometimes|required|string|max:20',
                'address' => 'sometimes|required|string|max:45',
                'phone' => 'sometimes|required|min:11|max:20',
                'float_minimum_order_amount'=>'integer',
                'float_max_delivery_distance'=>'integer',
                'float_delivery_fee'=>'integer',
                'store_avatar'=>'sometimes|required|max:255',
                'opening_time'=>'sometimes|required',
                'closing_time'=>'sometimes|required',
                //'password'=>'min:6',//promotion_info
            ]
        ];
    }


}
