<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\FoodsRequest;
use App\Models\Food;
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

    public function createOrEdit( Request $request )
    {
        app( FoodsRequest::class )->validate( $request );

        return $this->repository->save(
            $request->only(['name', 'description', 'category_id','cover_path']),
            $request->only(['specs']),
            $request->input('food_id', null)
        );
    }
}


