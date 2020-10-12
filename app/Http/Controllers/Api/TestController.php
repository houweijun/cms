<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Closure;
use Illuminate\Support\Facades\Storage;

class TestController extends CommonController
{
    public function index()
    {

      return $this->json_encode(1, 'api接口示例,嘻嘻');

    }



}
