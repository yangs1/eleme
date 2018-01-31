<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-1-31
 * Time: 下午3:56
 */

namespace App\Http\Controllers\Common;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function upload( Request $request )
    {
        //七牛测试
         // $disk = Storage::disk('qiniu');
          $a = Storage::putFileAs('', $request->file('photo'), "test_".$request->file('photo')->hashName());
var_dump($a);
    }
}