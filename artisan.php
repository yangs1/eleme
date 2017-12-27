<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 17-12-9
 * Time: 上午9:49
 */

require 'vendor/autoload.php';

$app = new \Foundation\Application();

$app->withFacades(true);

if (function_exists('apc_clear_cache')) {
    apc_clear_cache();
}
if (function_exists('opcache_reset')) {
    opcache_reset();
}

$worker = new \Foundation\Queue\Worker(
    $app['queue'], $app['events'], $app['Illuminate\Contracts\Debug\ExceptionHandler']
);

$worker->daemon("redis",'lsf', new \Foundation\Queue\WorkerOptions());
