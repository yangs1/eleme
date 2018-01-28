<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaxonRequest;
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
        $parent_id = $request->input('parent_id', null);
        if ($parent_id){
            $this->repository->addNote(
                $request->input('name'),
                $parent_id,
                $request->input('description', ''),
                $request->input('position', 0)
            );
        }else{
            $this->repository->addRootNode(
                $request->input('name'),
                $request->input('description', ''),
                $request->input('position', 0)
            );
        }
    }

    public function update( Request $request )
    {
        app( TaxonRequest::class )->scene('update')->validate( $request );

        $this->repository->updateNode(
            $request->input('node_id'),
            $request->only(['name', 'description', 'position'])
        );
    }

    public function delete( Request $request)
    {
        $this->repository->delNode(
            $request->input('node_id'),
            $request->input('force ', false)
        );
    }

    public function lists( Request $request)
    {
        $this->repository->getNodes(
            $request->input('level', null),
            $request->input('parent_id'. null)
        );
    }
}


