<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    /**
     * 后台成功跳转页面
     * @param null   $url     跳转路径
     * @param string $content 提示内容
     * @param int    $time    跳转提示的时间
     * @return $this
     */
    public function success($url = null, $content = '操作成功', $time = 3)
    {
        if ($url == null) {
            $url = $_SERVER['HTTP_REFERER'];
        }
        $data = [
            'code'    => 1,
            'url'     => $url,
            'content' => $content,
            'time'    => $time,
        ];
        return $data;
    }

    /**
     * 后台失败跳转页面
     * @param null   $url     跳转路径
     * @param string $content 提示内容
     * @param int    $time    跳转提示的时间
     * @return $this
     */
    public function error($url = null, $content = '操作失败', $time = 3)
    {
        if ($url == null) {
            $url = $_SERVER['HTTP_REFERER'];
        }
        $data = [
            'code'    => 0,
            'url'     => $url,
            'content' => $content,
            'time'    => $time,
        ];
        return $data;
    }

    /**
     * URL 重定向
     * $url 重定向 地址
     * @param array $with 隐式传参
     */
    public function redirect($url = '', $with = '操作失败')
    {
        if (empty($url)) {
            $response = redirect()->back()->with('code', $with);
        } else {
            $response = redirect($url)->with('code', $with);
        }


        throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
    }

    /**
     * 返回json数据
     * @param int $status 返回状态
     * @param     $msg    (array  string int) 返回信息
     * @return string
     */
    public function json_encode($status, $msg)
    {
        $arr['status']  = $status;
        $arr['message'] = $msg;
        $json           = json_encode($arr, JSON_UNESCAPED_UNICODE);
        return $json;
    }

    public function json_decode_very($data){
        if(!empty($data)){
            $data = json_decode($data,true);
            if(isset($data['status'])&&$data['status'] == 0){
                return true;
            }
        }
        return false;
    }
}
