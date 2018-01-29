<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 17-9-27
 * Time: 下午10:42
 */

$app->router->group([ 'namespace' => 'App\Http\Controllers\Auth','version'=>"v1",'as'=>'v1'], function () use($app) {

    $app->router->get('/login', ['uses'=>'UsersController@login', 'as'=>"user.login"]);
});

//'domain'=>'fccn.cc','middleware'=>['auth:a','session']  "middleware"=>['auth','session','cookie']

$app->router->group([ 'namespace' => 'App\Http\Controllers\Admin','version'=>"v1"], function () use($app) {

    $app->router->get('/', ['uses'=>'UsersController@userUnLock', 'as'=>"admin.lock"]);
    $app->router->get('/admin', ['uses'=>'StoresController@create', 'as'=>"admin.store.create"]);
});

$app->router->group([ 'namespace' => 'App\Http\Controllers\Store','version'=>"v1"], function () use($app) {

    $app->router->get('/store', ['uses'=>'StoresController@update', 'as'=>"store.update"]);
    $app->router->get('/foods', ['uses'=>'FoodsController@createOrEdit', 'as'=>'food.save']);
});