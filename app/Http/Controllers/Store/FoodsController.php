<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\FoodsRequest;
use App\Repository\FoodsRepository;
use Illuminate\Http\Request;

class FoodsController extends Controller
{
    protected $repository;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    */
    public function __construct( FoodsRepository $repository ){

        $this->repository = $repository;

    }

    public function create( Request $request )
    {
        app( FoodsRequest::class )->scene( 'create' )->validate( $request );
    }
}


