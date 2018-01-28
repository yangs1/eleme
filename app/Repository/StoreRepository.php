<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-1-5
 * Time: 下午2:30
 */
namespace App\Repository;

use App\Models\Store;
use App\Models\User;

class StoreRepository
{
    public function create( array $data, $user_id )
    {
        $store = Store::query()->create( $data );

        User::query()->where('id', $user_id)->update(['is_store_owner'=>1]);

        return $store;
    }


    public function lists( $page, $perPage ){

        return Store::query()->orderByDesc('created_at')->forPage( $page, $perPage )
            ->get(['id','name','address','latitude','longitude','phone',
                'category_original','category_sub','store_avatar','user_id']);
    }

    public function update( array $data, $store_id)
    {
        return Store::query()->where('id', $store_id )->update($data);
    }
}


