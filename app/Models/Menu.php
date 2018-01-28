<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 17-8-30
 * Time: 下午2:31
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model{

    public $table="menus";

    public $guarded = [];

    public function foods()
    {
        return $this->hasMany( Food::class);
    }
}