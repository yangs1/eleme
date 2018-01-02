<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 17-9-27
 * Time: 下午10:42
 */

$app->router->group([ 'namespace' => 'App\Http\Controllers\Auth','version'=>"v1"], function () use($app) {

    $app->router->get('/', 'UsersController@register');

});

//'domain'=>'fccn.cc','middleware'=>['auth:a','session']  "middleware"=>['auth','session','cookie']