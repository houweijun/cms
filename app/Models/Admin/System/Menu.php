<?php

namespace App\Models\Admin\System;

use App\Models\Common\ComModel;
use App\Models\Admin\System\RoleMenuLink;
use Session;

class Menu extends ComModel
{
    protected $table = 'admin_menu';
    protected $dateFormat = 'U';
    protected $guarded = ['updated_at', 'created_at'];

  /**
   * 获取菜单结构树
   */
  public function getMenuTree()
  {
    //查出角色Id
    $role_id = request()->session()->get('AdminUser')->authority_id;
    $menuLink = new RoleMenuLink();
    $menuIds = $menuLink->where('role_id', '=', $role_id)->value('menu_id');
    $menuIdArr = json_decode($menuIds, true);
    //假如是系统管理员 全部菜单查询
    if ($role_id == 1) {
      $menuData = $this->select('id', 'title', 'url', 'parent_id', 'grade', 'sort','iconclass')->orderBy('sort', 'asc')->get()->toArray();
    } else {
      $menuData = $this->select('id', 'title', 'url', 'parent_id', 'grade', 'sort','iconclass')->whereIn('id', $menuIdArr)->orderBy('sort', 'asc')->get()->toArray();
    }
    //获取菜单的访问权限
    $RoleUrl = array_pluck($menuData, 'url');
    //存入session记录
    session(['RoleUrl' => $RoleUrl]);
    $menuNew = [];
    //设置子菜单回调
    $callBack = function ($v) use ($menuData) {
      $arr = [];
      foreach ($menuData as $menu) {
        $menu['active'] = $menu['url'];
        $menu['url'] = url($menu['url']);
        if ($v['id'] == $menu['parent_id'] && $menu['grade'] == 1) {
          $arr[$menu['id'] . $menu['active']] = $menu;
        }
      }
      return $arr;
    };
    //分割菜单
    foreach ($menuData as $k => $v) {
      if ($v['parent_id'] == 0) {
        $v['active'] = $v['url'];
        $v['items'] = $callBack($v);
        $menuNew[$v['id'] . $v['url']] = $v;
      }
    }
    return $menuNew;
  }

}