<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 17-8-30
 * Time: 下午2:31
 */

namespace App\Models;

use App\Models\Traits\AuthenticatableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

/**
 * Class User
 * @package App
 * @see  \Illuminate\Database\MySqlConnection
 * @see  \Illuminate\Database\Connection
 * @see \Illuminate\Database\PostgresConnection
 * */
class User extends Model implements AuthenticatableContract{

    use AuthenticatableTrait;

    public $table="users";

    public $guarded = [];

   // public $dateFormat = 'U';

  //  public $dates = [];

    public function store()
    {
        return $this->hasOne( Store::class, 'user_id', 'id');
    }
}