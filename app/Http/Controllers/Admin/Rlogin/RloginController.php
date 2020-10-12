<?php
// +----------------------------------------------------------------------
// | Zhihuo [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 zhihuo All rights reserved.
// +----------------------------------------------------------------------
// | Author: liuxiaojin <935876982@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin\Rlogin;

use App\Http\Controllers\Common\CommonController;
use App\Models\Admin\System\Menu;
use App\Models\Admin\System\Org;
use App\Models\Admin\System\User;
use App\Events\AdminLoginSucceeded;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Event;
use Session;
use DB;

class RloginController extends CommonController
{
    /**
     * 用户登录
     * @param Request $request
     * @param Menu    $menu
     * @param Tree    $tree
     */
    public function login(Request $request, Menu $menu, User $user)
    {

        if ($request->isMethod('post')) {

            $data = $request->all();

            $password = sha1($data['password'] . config('vastlee.admin.security_key'));

            $userInfo = $user->where(['username' => $data['username'], 'password' => $password])->first();


            if (!empty($userInfo)) {
                //获取目录树结构

                if ($userInfo->status != '激活'|| $userInfo->is_system != 1) {
                    return $this->error('', '暂时没有登录权限');
                }

                //存入session记录
                session(['AdminUser' => $userInfo]);

              //获取结构记录
              $menus = $menu->getMenuTree();



                if (empty($menus)) {
                    return $this->error('', '暂时没有登录权限');
                }

                $menuStr = $this->getsubmenu($menus);


                // 管理员登录成功事件
                Event::fire(new AdminLoginSucceeded($userInfo->id));

                //菜单信息存入session  并返回后台首页
                session(['menuStr' => $menuStr]);
                return $this->success(url('admin/index'), '登录成功');

            } else {
                return $this->error('', '用户名或者密码错误');
            }


        }
        return view('admin/rlogin/login');
    }

    /**
     * 用户后台首页
     * @param Request $request
     * @param Menu    $menu
     * @param Tree    $tree
     */
    public function index()
    {

        return view('admin/rlogin/index');
    }


    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->success(url('admin/index'), '成功');

        }
        return view('admin/rlogin/add');
    }

    /**
     * 运营门户退出
     * @return $this
     */
    public function logout()
    {
        Session::forget('AdminUser');
        return $this->success(url('admin/login'), '退出成功');
    }

    public function listActive()
    {
        //过滤当前url访问地址
        $url = \Request::route()->uri;
        if (empty($url)) {
            session(['parent_id' => '']);
            session(['url_address' => '']);
            return false;
        }
        $urlNum = strpos($url, "{");
        if ($urlNum) {
            $url = substr($url, 0, $urlNum - 1);
        }
        $where     = [
            ['url', '=', $url],
            ['grade', '=', 1]
        ];
        $menu      = new Menu();
        $parent_id = $menu->where($where)->value('parent_id');
        if(!empty($parent_id)){
            session(['parent_id' => $parent_id]);
            session(['url_address' => $url]);
        }else{
            $parent_id = $menu->where([['url', '=', $url],['grade', '=', 2]])->value('parent_id');
            if(!empty($parent_id)){

                $data = $menu->where('id', '=', (int)$parent_id)->first();
                session(['parent_id' => $data->parent_id]);
                session(['url_address' => $data->url]);
            }else{
                session(['parent_id' => '']);
                session(['url_address' => '']);
            }

        }

    }

    /**
     * 获取顶级 菜单
     * @param $menus
     * @return string
     */
    public function getsubmenu($menus)
    {

        if (!empty($menus)) {
            $arr = [];
            foreach ($menus as $menu) {

                if (empty($menu['items'])) {
                    $str    = '<a href="' . $menu['url'] . '">
                        <i class="' . (!empty($menu['iconclass']) ? $menu['iconclass'] : 'fa fa-diamond') . '"></i>
                        <span class="nav-label">' . $menu['title'] . '</span>
                       
                        </a>';
                    $strSub = '';
                } else {
                    $str    = '
                        <a href="#">
                        <i class="' . (!empty($menu['iconclass']) ? $menu['iconclass'] : 'fa fa-diamond') . '"></i>
                         <span class="nav-label">' . $menu['title'] . '</span>
                         <span class="fa arrow"></span>
                        </a>';


                    $strSub = $this->getsubmenu1($menu['items']);
                }

                $strOutside = '<li data-parent_id="'.$menu['id'].'">' .
                    $str
                    . ' <ul class="nav nav-second-level collapse">'
                    . $strSub
                    . '</ul>
                    </li>';
                array_push($arr, $strOutside);
            }

            return implode('', $arr);
        } else {
            return '';
        }

    }

    /**
     * 获取二级 菜单
     * @param $menus
     * @return string
     */

    public function getsubmenu1($menus)
    {

        if (!empty($menus)) {
            $arr = [];
            foreach ($menus as $menu) {
                $str        = '<a href="' . $menu['url'] . '">
                   
                        ' . $menu['title'] . '
                 
                        </a>';
                $strOutside = '<li data-active="'.$menu['active'].'">' .
                    $str
                    .
                    '</li>';
                array_push($arr, $strOutside);
            }

            return implode('', $arr);
        } else {
            return '';
        }

    }

}
