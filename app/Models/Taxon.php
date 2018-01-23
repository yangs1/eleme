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

/**
 * Class User
 * @package App
 * @see  \Illuminate\Database\MySqlConnection
 * @see  \Illuminate\Database\Connection
 * @see \Illuminate\Database\PostgresConnection
 * @method Model find( mixed  $id, array  $columns = null ) static
 * @method Model create( mixed  $arr ) static
 * @method \Illuminate\Database\Eloquent\Builder  where($column, $operator = null, $value = null, $boolean = 'and') static
 */
class Taxon extends Model{

    use BatchUpdateTrait;

    public $table="taxon";

    public $guarded = [];
}