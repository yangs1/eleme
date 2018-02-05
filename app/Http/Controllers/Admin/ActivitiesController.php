<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-2-3
 * Time: 下午3:34
 */

namespace App\Http\Controllers\Admin;


class ActivitiesController
{
    protected $repository;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    */
    public function __construct(  $repository )
    {
        $this->repository = $repository;
    }
}