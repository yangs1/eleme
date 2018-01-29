<?php

namespace Illuminate\Auth;

use App\Models\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Str;

class TokenGuard implements Guard
{
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The name of the query string item from the request containing the API token.
     *
     * @var string
     */
    protected $inputKey;

    /**
     * The name of the token "column" in persistent storage.
     *
     * @var string
     */
    protected $storageKey;

    protected $ttl = 60;

    protected $fields = ['id']; //, 'name', 'signature', 'avatar', 'gender','is_store_owner'


    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider; // Illuminate\Auth\EloquentUserProvider
        $this->inputKey = 'api_token';
        $this->storageKey = 'remember_token';
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \App\Models\User|null
     *
     * 初步设想， 用户 跟 店铺，登录 user 以切换角色方式 的方式存在
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (! empty($token)) {
            /*$user = $this->provider->retrieveByCredentials(
                [$this->storageKey => $token]
            );*/
            $user = $this->parseJWT( $token );
        }

        return $this->user = $user;
    }


    public function store()
    {
        $user = $this->user();

        if( $user && $user->is_store_owner ){

            return $user->store;

        }
        return null;
    }
    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        $token = $this->request->query($this->inputKey);

        if (empty($token)) {
            $token = $this->request->input($this->inputKey);
        }

        if (empty($token)) {
            $token = $this->request->bearerToken();
        }

        if (empty($token)) {
            $token = $this->request->getPassword();
        }

        return $token;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials[$this->inputKey])) {
            return false;
        }

        $credentials = [$this->storageKey => $credentials[$this->inputKey]];

        if ($user = $this->provider->retrieveByCredentials($credentials)) {

            $this->login( $user );

            return true;
        }

        return false;
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  $single
     * @return string
     */
    public function login( AuthenticatableContract $user, $single = false )
    {
        //$this->forgetCache( "$guard:".$user->getAuthIdentifier() );

        $single && $this->cycleRememberToken( $user );// 开启即为单端登录

        $this->setUser($user);

        return $this->refreshToken( $user );
    }

    /**
     * Refresh the "remember me" token for the user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function cycleRememberToken(AuthenticatableContract $user)
    {
        $user->setRememberToken($token = session_create_id());

        $this->provider->updateRememberToken($user, $token);
    }

    public function refreshToken(AuthenticatableContract $user){

        $secret = session_create_id();

        $token = $this->_getJWTToken( $this->_JWTHeader(), $this->_JWTPayload( $user, $secret ), $user->getRememberToken() );

        //cache()->set('remember_token:'.$user->getRememberToken(), json_encode(['user_id'=>$user->getAuthIdentifier()]), 60*24 );
        cache()->set('remember_token:'.$secret, $user->getAuthIdentifier(), $this->ttl );

        if ( !cache()->has( "user:".$user->getAuthIdentifier())){

            cache()->put( "user:".$user->getAuthIdentifier(), \swoole_serialize::pack($user), $this->ttl*24 );

        }

        return $token;
    }

    public function forgetCache( $key )
    {
        cache()->forget( $key );
    }

   /* public function updateApiToken( $value )
    {
        $this->user()->{$this->storageKey} = $value;
    }*/

    public function parseJWT( $jwt )
    {
        $jwtArr = explode('.', $jwt);
        if (count($jwtArr) != 3){
            return null;
        }
        $payload = json_decode( base64_decode( $jwtArr[1] ), true );

        /*
         * TODO
         * 进一步处理， 过期时间， 异地登录
         * */
        if ( isset( $payload['jti'], $payload['exp']) && $payload['exp'] > time() ){

            //$userCache = cache()->get( decrypt($payload['jti']) );
            $user = $this->_getUserBySecret( $payload['jti'] );

           // var_dump($jwtArr[0].$jwtArr[1].$this->user()->getRememberToken());
            if ($user instanceof AuthenticatableContract){
                return  hash_equals(
                    base64_decode( $jwtArr[2]),
                    hash_hmac("sha1", $jwtArr[0].$jwtArr[1], $user->getRememberToken())
                ) ? $user : null;
            }

        }
        return null;
    }

    /**
     * Set the current request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }


    private function _getUserBySecret( $secret )
    {
        /*$userArr = json_decode( cache()->get( 'remember_token:'.$token ), true);

        $user_id =  is_array($userArr) ? $userArr['user_id'] : null;

        if ($user_id){
            return cache()->get( "user:".$user_id);
        }*/

        $remember_token = cache()->get( 'remember_token:'.$secret );

        if ($remember_token){
            $user_serialize =  cache()->get( "user:".$remember_token );

            if ( $user_serialize ){
                return \swoole_serialize::unpack( $user_serialize );
            }

        }
        return null;
    }

    private function _getJWTToken( $header, $payload, $token )
    {
        $components = [
            $header,
            $payload,
            base64_encode(hash_hmac("sha1", $header.$payload, $token))
        ];
        return implode(".", $components);
    }

    private function _JWTHeader(){
        return base64_encode(json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]));
    }

    private function _JWTPayload( AuthenticatableContract $user, $secret){
        $payload = [
            //'aud' => , //所面向的用户
            //'sub' => $user->account, //接收jwt的一方,
            'iat' => $_SERVER['REQUEST_TIME'], //什么时候签发的
            'exp' => $_SERVER['REQUEST_TIME'] + 3600, //过期时间
            'jti' => $secret,// $user->getRememberToken(),//
        ];
        return base64_encode(json_encode($payload));
    }
}
