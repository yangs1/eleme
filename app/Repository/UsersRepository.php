<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-1-5
 * Time: 下午2:30
 */
namespace App\Repository;

use App\Models\User;

class UsersRepository
{
    public function lists( $page, $perPage ){

        return User::orderByDesc('created_at')->forPage( $page, $perPage )
            ->get(['name', 'account', 'city', 'gender', 'avatar', 'is_store_owner', 'is_lock']);
    }
}