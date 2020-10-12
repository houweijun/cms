<?php
// +----------------------------------------------------------------------
// | Zhihuo [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 zhihuo All rights reserved.
// +----------------------------------------------------------------------
// | Author: liuxiaojin <935876982@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin\Asset;

use App\Http\Controllers\Common\CommonController;
use Bootstrap\Common\Result;
use Illuminate\Http\Request;
use Bootstrap\Common\Oss;
use Illuminate\Support\Facades\Storage;
use Excel;

class webUpLoaderController extends CommonController
{

    public $path;

    //渲染文件上传
    public function index()
    {
        return view('admin/asset/webuploader');
    }

    //文件上传处理
    public function upload(Request $request)
    {
        $tmp_name = $_FILES["file"]["tmp_name"];
        $type     = $_FILES["file"]["type"];
        if (empty($tmp_name) || empty($type)) {
            return json_encode(['status' => 0, 'message' => '上传失败'], true);
        }
        //获取旧图片，从本地上删除
        $old_ikeys = $request->input('old_ikeys');
        if (!empty($old_ikeys)) {
          del_upload_file($old_ikeys);
        }
        $type              = trim(strstr($type, '/'), '/');
        $ikey              = date('Ymd') . '/' . rand(1000, 9999) . uniqid() . '.' . $type;

       create_upload_file($ikey,$tmp_name);

        $data['thumbnail'] = $ikey;

        return json_encode(['status' => 1, 'message' => $data], true);

    }

    /**
     * 删除阿里云上传图片
     * @param Request $request
     * @param Result  $result
     * @return string
     */
    public function cancel(Request $request, Result $result)
    {

        //判断请求参数是否为空
        $data = $request->all();
        if (empty($data)) {
            $result->status  = 0;
            $result->message = '请求参数错误';
            return $result->toJson();
        }

        if (!$request->has('id')) {
            //删除活动图片 type 1
            if ($request->has('thumbnail')) {
                $ikeys = $request->input('thumbnail');
                try {
                    Oss::delImg($ikeys);

                } catch (\Exception $e) {

                }
                $result->status  = 1;
                $result->message = '删除成功';
                return $result->toJson();
            }
        }

    }

    /**
     * 导入上传文件
     */
    public function lead(Request $request)
    {

        if ($request->isMethod('GET')) {
            return view('admin/asset/lead');
        }

        $file = $_FILES['file'];
        $ext  = getSuffix($file['name']);
        switch (true) {
            case ($ext == 'txt'):
                try {
                    $content = file_get_contents($file['tmp_name']);
                    $content = trim($content);
                    $content = explode("\r\n", $content);
                    $data = [];
                    foreach ($content as $v) {
                        if(!empty($v)){
                            $data[] = $v;
                        }
                    }
                    $data = json_encode($data,true);
                    return $this->json_encode(1, $data);
                } catch (\Exception $e) {
                    return $this->json_encode(0, 'txt上传失败');
                }
                break;
            case ($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv'):
                try {
                    $excel_file_path = $file['tmp_name'];
                    $content         = file_get_contents($excel_file_path);
                    $fileType        = mb_detect_encoding($content, ['UTF-8', 'GBK', 'LATIN1', 'BIG5']);

                    $res = Excel::load($excel_file_path, function ($reader) {

                    }, $fileType)->get()->toArray();

                    $data = [];
                    foreach ($res as $v) {
                        if(!empty($v[0])){
                            $data[] = $v[0];
                        }
                    }
                    if (empty($data[0])) {
                        return $this->json_encode(0, '表格数据没空,表格请加入数据');
                    }
                    $data = json_encode($data,true);
                    return $this->json_encode(1, $data);

                } catch (\Exception $e) {
                    return $this->json_encode(0, $e->getMessage());

                }

                break;
        }

    }


}
