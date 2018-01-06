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
        $store = Store::create( $data );

        User::where('id', $user_id)->update(['is_store_owner'=>1]);
    }
}