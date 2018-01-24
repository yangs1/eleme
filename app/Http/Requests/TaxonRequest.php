<?php

namespace App\Http\Requests;

use Foundation\Concerns\FromRequests;
use Illuminate\Http\Request;

class TaxonRequest extends FromRequests
{
    public function rules()
    {
        return [];
    }

    public function sceneRules()
    {
        return [
            'create'=> [
                'name' => 'required|max:45',
                'description' => 'string|max:100',
                'position' => 'numeric',
                'parent_id' => 'numeric'
            ],
            'update'=> [
                'name' => 'sometimes|required|max:45',
                'description' => 'sometimes|max:100',
                'position' => 'numeric',
                'node_id'=> 'required'
            ],
        ];
    }

}
