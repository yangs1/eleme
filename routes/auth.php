<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 17-9-27
 * Time: 下午10:42
 */

$app->router->group([ 'namespace' => 'App\Http\Controllers\Auth','version'=>"v1"], function () use($app) {

    $app->router->get('/login', 'UsersController@login');
});

//'domain'=>'fccn.cc','middleware'=>['auth:a','session']  "middleware"=>['auth','session','cookie']

$app->router->group([ 'namespace' => 'App\Http\Controllers\Admin','version'=>"v1"], function () use($app) {

    $app->router->get('/', 'UsersController@userUnLock');
    $app->router->get('/admin', 'StoresController@create');
});

$app->router->group([ 'namespace' => 'App\Http\Controllers\Store','version'=>"v1"], function () use($app) {

    $app->router->get('/store', 'StoresController@update');
    $app->router->get('/foods', 'FoodsController@create');
});