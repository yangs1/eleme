<?php

namespace App\Http\Requests;

use Foundation\Concerns\FromRequests;
use Illuminate\Http\Request;

class FoodsRequest extends FromRequests
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
                'category_id' => 'required|integer',
                'cover_path' => 'required|string|max:255',

                'specs' => 'required|array',
                'specs.*.name' => 'required|max:20',
                'specs.*.price' => 'required|numeric',
                'specs.*.cover_path' => 'max:255',
                'specs.*.store_count' => 'integer|max:9999',
            ],
            'update'=> [
                'name' => 'sometimes|required|max:45',
                'description' => 'sometimes|max:100',
                'category_id' => 'sometimes|required|integer',
                'cover_path' => 'sometimes|required|max:255',
                'food_id' => 'required|integer',

                'specs' => 'sometimes|required|array',
                'specs.*.name' => 'required|max:20',
                'specs.*.price' => 'required|numeric',
                'specs.*.sku_id' => 'sometimes|integer',
                'specs.*.cover_path' => 'max:255',
                'specs.*.store_count' => 'integer|max:9999',
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidate( Request $request )
    {
        $this->scene = $request->input('food_id' ) ? 'update' : 'create';
    }
}
