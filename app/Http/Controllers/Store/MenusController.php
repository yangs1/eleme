<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\FoodsRequest;
use App\Models\Food;
use App\Repository\FoodsRepository;
use App\Repository\MenusRepository;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    protected $repository;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    */
    public function __construct( MenusRepository $repository ){

        $this->repository = $repository;

    }

    public function create( Request $request )
    {

    }
}


