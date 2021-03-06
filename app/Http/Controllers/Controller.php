<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 17-9-18
 * Time: 上午11:41
 */

namespace App\Http\Controllers;

use Foundation\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse(['code'=>422, 'result'=>$errors, 'message'=>trans('validation.error_message')], 422);
    }

    protected function response($message = '', $data = null,  $status = 200,  $headers = [], $options = 0){
        return new JsonResponse([ 'code'=>$status, 'result'=>$data, 'message'=>$message ], 200, $headers, $options);
    }
}