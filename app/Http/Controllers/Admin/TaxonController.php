<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repository\TaxonRepository;
use Illuminate\Http\Request;

class TaxonController extends Controller
{
    protected $repository;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    */

    public function __construct( TaxonRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create( Request $request )
    {
        /*return Taxon::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'parent_id' => $request->input('description'),
        ]);*/
    }
}


