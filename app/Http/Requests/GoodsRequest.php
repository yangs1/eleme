<?php

namespace App\Http\Requests;

use Foundation\Concerns\FromRequests;

class GoodsRequest extends FromRequests
{
    public function rules()
    {
        return [

        ];
    }

    public function sceneRules()
    {
        return [
            'create'=> [
                'name' => 'required|string|max:45',
                'description'
            ],
        ];
    }


}
