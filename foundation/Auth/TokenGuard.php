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
     * @param  $guard
     * @return string
     */
    public function login(AuthenticatableContract $user, $guard = 'user')
    {
        $this->forgetCache( "$guard:".$user->getAuthIdentifier() );

        $this->cycleRememberToken( $user );

        $this->setUser($user);

        return $this->updateCache( $user, $guard );
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

    public function updateCache(AuthenticatableContract $user, $guard){

        $token = $this->updateJWTToken( $this->JWTHeader(), $this->JWTPayload( $user, $guard ), $user->getRememberToken() );

        cache()->put( "$guard:".$user->getAuthIdentifier(), serialize($user), 60);

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

    public function updateJWTToken( $header, $payload, $token )
    {
       $components = [
           $header,
           $payload,
           base64_encode(hash_hmac("sha1", $header.$payload, $token))
       ];
       return implode(".", $components);
    }



    protected function JWTHeader(){
        return base64_encode(json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]));
    }

    protected function JWTPayload( AuthenticatableContract $user, $guard){
        $payload = array_merge(
            [
                'aud' => $this->request->ip(), //接收者
                'sub' => $user->getAuthIdentifier(),
                'iat' => $_SERVER['REQUEST_TIME'], //什么时候签发的
                'exp' => $_SERVER['REQUEST_TIME'] + 3600, //过期时间
                'guard' => $guard
                //'jti' => encrypt($user->getRememberToken())
            ]
            , $user->only( $this->fields )
        );
        return base64_encode(json_encode($payload));
    }


    public function parseJWT( $jwt )
    {
        $jwtArr = explode('.', $jwt);
        if (count($jwtArr) != 3){
            return null;
        }
        $this->payload = json_decode( base64_decode( $jwtArr[1] ), true );

        /*
         * TODO
         * 进一步处理， 过期时间， 异地登录
         * */
        if (isset( $this->payload['sub'], $this->payload['guard'] )){

            //$userCache = cache()->get( decrypt($payload['jti']) );
            $userCache = cache()->get( $this->payload['guard'].":".$this->payload['sub'] );

            $user = $userCache ? unserialize($userCache) : null;

            $rememberToken = $user ? $user->getRememberToken() : '-';
           // var_dump($jwtArr[0].$jwtArr[1].$this->user()->getRememberToken());
            return  hash_equals(
                base64_decode( $jwtArr[2]),
                hash_hmac("sha1", $jwtArr[0].$jwtArr[1], $rememberToken)
            ) ? $user : null;
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
}
