<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Models\User;
use App\Repository\StoreRepository;
use App\Repository\UsersRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UsersController extends Controller
{
    protected $repository;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    */
    public function __construct( UsersRepository $repository )
    {
        $this->repository = $repository;
    }

    public function lists( Request $request ){
        $page = $request->input('page',1);
        $perPage = $request->input('perPage', 10);

        $this->repository->lists( $page, $perPage );
    }
}


