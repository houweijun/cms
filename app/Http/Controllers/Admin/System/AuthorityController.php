<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Common\CommonController;
use App\Models\Admin\System\Menu;
use App\Models\Admin\System\Role;
use App\Models\Admin\System\RoleMenuLink;
use Bootstrap\Common\Result;
use Bootstrap\Common\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Http\Controllers\Controller;

class AuthorityController extends CommonController
{

    /**
     * 权限管理首页
     * @param Request      $request
     * @param RoleMenuLink $menuLink
     * @param Role         $role
     */
    public function index(Request $request, RoleMenuLink $menuLink, Role $role)
    {
        $param = $request->all();

        $where = $role;

        //筛选管理员账号
        if (isset($param['name']) && !empty($param['name'])) {
            $where = $where->where('name', 'like', '%' . trim($param['name']) . '%');
        }

        $data = $where->paginate(20);

        return view('admin/system/authority/index', [
            'data' => $data,
            'name' => isset($param['name']) ? $param['name'] : '',
        ]);

    }

    /**
     * 权限添加
     * @param Request      $request
     * @param RoleMenuLink $menuLink
     * @param Role         $role
     * @param Menu         $menu
     */
    public function add(Request $request, RoleMenuLink $menuLink, Role $role, Menu $menu, Tree $tree)
    {
        //如果是get请求 渲染页面
        if ($request->isMethod('get')) {

            $tree->icon = ['│ ', '├─ ', '└─ '];
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

            $result = $menu->get()->toArray();

            $newMenus = [];
            foreach ($result as $k => $m) {
                $newMenus[$m['id']]         = $m;
                $newMenus[$m['id']]['name'] = $m['title'];
                $result[$k]['name']         = $m['title'];
            }

            foreach ($result as $n => $t) {

                $result[$n]['checked']      = '';
                $result[$n]['level']        = $this->_getLevel($t['id'], $newMenus);
                $result[$n]['style']        = empty($t['parent_id']) ? '' : 'display:none;';
                $result[$n]['parentIdNode'] = ($t['parent_id']) ? ' class="child-of-node-' . $t['parent_id'] . '"' : '';
            }


            $str = "<tr id='node-\$id'\$parentIdNode  style='\$style'>
                   <td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuId[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$name</td>
    			</tr>";
            $tree->init($result);

            $category = $tree->getTree(0, $str);

            return view('admin/system/authority/add', [
                'category' => $category
            ]);
        }

        //接收数据
        $data = $request->all();

        //验证数据有效性
        $validator = $this->validatePost($data);

        //查询数据库 活动开启数量
        $roleCount = $role->where('name', $data['name'])->count();

        if ($roleCount >= 1) {
            $validator->after(function ($validator) {
                $validator->errors()->add('name', '已经存在,请重新输入模板名称');
            });
        }

        if ($validator->fails()) {
            $warnings = $validator->messages();
            $content  = $warnings->first();
            return $this->error('', $content);
        }

        $data1 = [
            'name'        => $data['name'],
            'description' => $data['description'],
            'status'      => 1,
            'is_system'   => 1,
            'created_at'  => time(),
        ];

        //开启事务
        DB::beginTransaction();
        //数据入库
        $id = $role->insertGetId($data1);
        if ($id) {
            $data2 = [
                'role_id'    => $id,
                'menu_id'    => json_encode($data['menuId']),
                'created_at' => time()
            ];
            $res   = $menuLink->insertGetId($data2);
            if ($res) {
                $bo = true;
            } else {
                $bo = false;
                DB::rollback();//回滚事务
            }
        } else {
            $bo = false;
            DB::rollback();//回滚事务
        }

        if ($bo) { //数据入库成功操作
            DB::commit();//提交事务
            return $this->success(url('admin/system/authority/index'), '添加成功');
        } else {
            return $this->error('', '添加失败，请重新添加');
        }
    }


    /**
     * 权限编辑
     * @param Request      $request
     * @param RoleMenuLink $menuLink
     * @param Role         $role
     * @param Menu         $menu
     */
    public function edit($id, Request $request, RoleMenuLink $menuLink, Role $role, Menu $menu, Tree $tree)
    {
        //如果是get请求 渲染页面
        if ($request->isMethod('get')) {
            $data = $role->where('id', $id)->first();

            $menuLinkData = $menuLink->where('role_id', $id)->orderBy('id', 'desc')->first();

            $menuData = json_decode($menuLinkData['menu_id'], true);

            $tree->icon = ['│ ', '├─ ', '└─ '];
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

            $result = $menu->get()->toArray();


            $newMenus = [];
            foreach ($result as $k => $m) {
                $newMenus[$m['id']]         = $m;
                $newMenus[$m['id']]['name'] = $m['title'];
                $result[$k]['name']         = $m['title'];
            }

            foreach ($result as $n => $t) {
                $result[$n]['checked'] = ($this->_isChecked($t['id'], $menuData)) ? ' checked' : '';;
                $result[$n]['level']        = $this->_getLevel($t['id'], $newMenus);
                $result[$n]['style']        = empty($t['parent_id']) ? '' : 'display:none;';
                $result[$n]['parentIdNode'] = ($t['parent_id']) ? ' class="child-of-node-' . $t['parent_id'] . '"' : '';
            }


            $str = "<tr id='node-\$id'\$parentIdNode  style='\$style'>
                   <td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuId[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$name</td>
    			</tr>";
            $tree->init($result);

            $category = $tree->getTree(0, $str);

            return view('admin/system/authority/edit', [
                'category' => $category,
                'data'     => $data
            ]);
        }

        //接收数据
        $data = $request->all();

        //验证数据有效性
        $validator = $this->validatePost($data);

        //查询数据库 活动开启数量
        $roleCount = $role->where('name', $data['name'])->count();

        if ($roleCount > 1) {
            $validator->after(function ($validator) {
                $validator->errors()->add('name', '已经存在,请重新输入模板名称');
            });
        }

        if ($validator->fails()) {
            $warnings = $validator->messages();
            $content  = $warnings->first();
            return $this->error('', $content);
        }

        $data1 = [
            'name'        => $data['name'],
            'description' => $data['description'],
            'status'      => 1,
            'is_system'   => 1,
            'updated_at'  => time(),
        ];

        //开启事务
        DB::beginTransaction();
        //数据入库
        $re = $role->where('id', $id)->update($data1);
        if ($re) {
            $data2 = [
                'role_id'    => $id,
                'menu_id'    => json_encode($data['menuId']),
                'updated_at' => time()
            ];
            $res   = $menuLink->where('role_id', $id)->update($data2);
            if ($res) {
                $bo = true;
            } else {
                $bo = false;
                DB::rollback();//回滚事务
            }
        } else {
            $bo = false;
            DB::rollback();//回滚事务
        }

        if ($bo) { //数据入库成功操作
            DB::commit();//提交事务
            return $this->success(url('admin/system/authority/index'), '编辑成功');
        } else {
            return $this->error('', '编辑失败，请重新编辑');
        }
    }


    /**
     * ajax 删除
     * @param Request $request
     * @param User    $user
     * @param Result  $result
     */
    public function delete(Request $request, RoleMenuLink $menuLink, Role $role, Result $result)
    {
        $data = $request->all();
        if($data['id'] ==1){
            $result->status  = 0;
            $result->message = '系统管理员不可以删除';
            return $result->toJson();
        }
        //开启事务
        DB::beginTransaction();
        //数据入库
        $res = $role->where('id', $data['id'])->update(['deleted_at' => time()]);
        if ($res) {
            $res1 = $menuLink->where('role_id', $data['id'])->update(['deleted_at' => time()]);
            if ($res1) {
                $bo = true;
            } else {
                $bo = false;
                DB::rollback();//回滚事务
            }
        } else {
            $bo = false;
            DB::rollback();//回滚事务
        }

        if ($bo) { //数据入库成功操作
            DB::commit();//提交事务
            $result->status  = 1;
            $result->message = '删除成功';
        } else {
            $result->status  = 0;
            $result->message = '删除失败';
        }

        return $result->toJson();
    }

    /**
     * 获取菜单深度
     * @param       $id
     * @param array $array
     * @param int   $i
     * @return int
     */
    protected function _getLevel($id, $array = [], $i = 0)
    {
        if ($array[$id]['parent_id'] == 0 || empty($array[$array[$id]['parent_id']]) || $array[$id]['parent_id'] == $id) {
            return $i;
        } else {
            $i++;
            return $this->_getLevel($array[$id]['parent_id'], $array, $i);
        }
    }

    /**
     * 检查指定菜单是否有权限
     * @param array $menu menu表中数组
     * @param       $$menuData
     * @return bool
     */
    private function _isChecked($menu, $menuData)
    {
        if ($menuData) {
            if (in_array($menu, $menuData)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * 数据验证
     * @param array $data
     * @return mixed
     */
    public function validatePost(array $data)
    {
        $rules   = [
            'name'   => 'required',
            'menuId' => 'required|array',
        ];
        $message = [
            'name.required'   => ':attribute必须填',
            'menuId.required' => '请勾选:attribute',
        ];
        $field   = [
            'name'   => '模板名称',
            'menuId' => '权限选择',
        ];
        return Validator::make($data, $rules, $message, $field);
    }
}
