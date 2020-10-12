<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Common\CommonController;
use Closure;
use App\Http\Controllers\Admin\Rlogin\RloginController;

class Login extends CommonController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty($request->session()->get('AdminUser'))) {
            return $this->redirect(url('/admin/login'));
        }

        //获取动态当前活动session list Active
        $newRlogin = new RloginController();
        $newRlogin->listActive();

        $AdminUser = $request->session()->get('AdminUser');
        //假如不是超级管理员 权限验证
        if ($AdminUser->authority_id != 1) {
            //过滤当前url访问地址
            $url    = $request->route()->uri;
            $urlNum = strpos($url, "{");
            if ($urlNum) {
                $url = substr($url, 0, $urlNum - 1);
            }
            //获取当前角色访问的全部url路径
            $RoleUrl = $request->session()->get('RoleUrl')->toArray();
            //加的Url路由
            array_push($RoleUrl, 'admin/index');
            array_push($RoleUrl, 'admin/system/repassword');
            array_push($RoleUrl, 'admin/logout');
            array_push($RoleUrl, 'admin/asset/webuploader');
            array_push($RoleUrl, 'admin/asset/upload');
            array_push($RoleUrl, 'admin/asset/cancel');
            array_push($RoleUrl, 'admin/asset/lead');

            //假如没有访问权限 返回路径
            if (empty($RoleUrl) || !in_array($url, $RoleUrl)) {
                return $this->redirect('','非法操作');
            }
        }


        return $next($request);
    }
}
