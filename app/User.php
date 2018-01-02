<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 17-8-30
 * Time: 下午2:31
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

/**
 * Class User
 * @package App
 * @mixin \Illuminate\Database\MySqlConnection|\Illuminate\Database\PostgresConnection
 * @method \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null find(mixed  $id, array  $columns = null) static
 * @method \Illuminate\Database\Eloquent\Builder where($column, $operator = null, $value = null, $boolean = 'and') static
 */
class User extends Model implements AuthenticatableContract{
    public $table="users";

    /**
     * The column name of the "remember me" token.
     *
     * @var string
     */
    protected $rememberTokenName = 'remember_token';

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        if (! empty($this->getRememberTokenName())) {
            return $this->{$this->getRememberTokenName()};
        }
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        if (! empty($this->getRememberTokenName())) {
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return $this->rememberTokenName;
    }


}