<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 17-8-30
 * Time: 下午2:31
 */

namespace App\Models;

use App\Models\Traits\AuthenticatableTrait;
use App\Models\Traits\BatchUpdateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Food extends Model{

    use BatchUpdateTrait;

    public $table="foods";

    public $guarded = [];

}