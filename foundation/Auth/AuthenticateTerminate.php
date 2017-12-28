<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 17-12-28
 * Time: 上午9:13
 */

namespace Foundation\Auth;

use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class AuthenticateTerminate
{
    /**
     * The guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        return $next($request);
        //return $this->auth->guard($guard)->basic() ?: $next($request);
    }

    public function terminate($request, $response)
    {
        $this->auth->flush();
    }
}