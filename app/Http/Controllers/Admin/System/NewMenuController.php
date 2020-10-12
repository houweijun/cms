<?php
// +----------------------------------------------------------------------
// | Zhihuo [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 zhihuo All rights reserved.
// +----------------------------------------------------------------------
// | Author: liuxiaojin <935876982@qq.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\Rlogin\RloginController;
use App\Http\Controllers\Common\CommonController;
use App\Models\Admin\System\Menu;
use App\Models\Admin\System\RoleMenuLink;
use Bootstrap\Common\Result;
use Bootstrap\Common\Tree;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class NewMenuController extends CommonController
{
    /**
     * 新菜单首页展示
     * @param Request $request
     * @param Menu    $menu
     */
    public function index(Request $request, Menu $menu, Tree $tree)
    {
        $param = $request->all();
        $where = $menu;
        //搜索词不为空
        if (isset($param['title']) && !empty($param['title'])) {
            $where    = $where->where('title', 'like', '%' . trim($param['title']) . '%');
            $category = $where->orderBy('sort', 'asc')->paginate(10);

        } else { //搜索词为空   显示目录树
            $data       = $where->orderBy('sort', 'asc')->get()->toArray();
            $tree->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';


            foreach ($data as $key => $value) {
                $data[$key]['parent_id_node'] = ($value['parent_id']) ? ' class="child-of-node-' . $value['parent_id'] . '"' : '';
                $data[$key]['style']          = empty($value['parent_id']) ? '' : 'display:none;';
                $data[$key]['created_at']     = date('Y-m-d H:i:s');
                $data[$key]['add_submenu']    = '<a class="btn btn-outline btn-primary mr5" href="' . url('admin/system/newmenu/add', [$value['id']]) . '"><i class="fa fa-plus-square-o"></i>添加子菜单</a>';
                $data[$key]['edit']           = '<a class="btn btn-outline btn-warning mr5" href="' . url('admin/system/newmenu/edit', [$value['id']]) . '"><i class="fa fa-paste"></i>编辑</a>';
                $data[$key]['delete']         = '<a class="btn btn-outline btn-danger mr5" href="javascript:;"
                           onclick="return checkDel(\'删除菜单\',\'你确定要删除菜单' . $value['title'] . '吗?\',\'/admin/system/newmenu/del\',this)"
                           data-id="' . $value['id'] . '">
                           <i class="fa fa-trash-o fa-lg"></i>删除
                        </a>';
                $data[$key]['order']          = '<input name="sort" type="text" size="3" value="' . $value['sort'] . '"
                                     class="input input-order" data-id="' . $value['id'] . '" onchange="listOrder(this,\'/admin/system/newmenu/order\')" />';
            }

            $tree->init($data);

            //排序权限判别
            if(roleShow('admin/system/newmenu/order')){
                $str1="<td style='padding-left:20px'>\$order</td>";
            }else{
                $str1 = '';
            }

            //操作权限判别
            if(roleShow('admin/system/newmenuadd')||roleShow('admin/system/newmenu/edit')||roleShow('admin/system/newmenu/del')){
                //菜单添加按钮
                if(roleShow('admin/system/newmenu/add')){
                    $str3 = "\$add_submenu";
                }else{
                    $str3= '';
                }
                //菜单编辑按钮
                if(roleShow('admin/system/newmenu/edit')){
                    $str4 = "\$edit";
                }else{
                    $str4= '';
                }
                //菜单删除按钮
                if(roleShow('admin/system/newmenu/del')){
                    $str5 = "\$delete";
                }else{
                    $str5= '';
                }
                $str2 = "<td>".$str3.$str4.$str5."</td>";
            }else{
                $str2 ='';
            }
            //菜单组合
            $str = "<tr id='node-\$id' \$parent_id_node style='\$style'>".
                        $str1
                        ."<td>\$id</td>
                        <td>\$spacer\$title</td>
                        <td>\$url</td>
                        <td>\$description</td>
                        <td>\$created_at</td>
                        ".$str2."
                    </tr>";

            $category = $tree->getTree(0, $str);
        }


        return view('admin/system/newmenu/index', [
            'title'    => isset($param['title']) ? $param['title'] : '',
            'category' => $category
        ]);
    }

    /**
     * 新菜单添加
     * @param Request $request
     * @param Menu    $menu
     */
    public function add(Request $request, Menu $menu, $parentId = '0', Tree $tree, RloginController $newRloginController)
    {
        if ($request->isMethod('get')) {
            $data  = $menu->orderBy('sort', 'asc')->get()->toArray();
            $array = [];
            foreach ($data as $k => $v) {
                $v['selected'] = $v['id'] == $parentId ? 'selected' : '';
                $array[]       = $v;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
            $tree->init($array);
            $selectCategory = $tree->getTree(0, $str);
            return view('admin/system/newmenu/add', [
                'select_category' => $selectCategory
            ]);
        }

        //接收数据
        $data = $request->all();
        $data = getData($data);

        //验证数据有效性
        $validator = $this->validatePost($data);
        $titleNum  = $menu->where('title', $data['title'])->count();
        if ($titleNum >= 1) {
            $validator->after(function ($validator) {
                $validator->errors()->add('title', '数据库中已经存在相同菜单名称,请重新输入');
            });
        }
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $content  = $warnings->first();
            return $this->error('', $content);
        }
        $data['created_at'] = time();

        if ((int)$data['parent_id'] == 0) {
            $data['url']   = "#";
            $data['grade'] = 0;
        } else {
            $grade         = $menu->where('id', $data['parent_id'])->first();
            $data['grade'] = $grade['grade'] + 1;
        }

        $id = $menu->insertGetId($data);
        //菜单入库判断是否正确
        if ($id) {
            //获取结构记录
            $menus = $menu->getMenuTree();
            $menuStr =  $newRloginController->getsubmenu($menus);
            //菜单信息存入session 更新菜单
            session(['menuStr' => $menuStr]);
            return $this->success(url('admin/system/newmenu/index'), '添加成功');
        } else {
            return $this->error('', '添加失败，请重新添加');
        }
    }

    /**
     * 新菜单编辑
     * @param Request $request
     * @param Menu    $menu
     */
    public function edit(Request $request, Menu $menu, $id, Tree $tree, RloginController $newRloginController)
    {
        if ($request->isMethod('get')) {
            $data  = $menu->orderBy('sort', 'asc')->get()->toArray();
            $rs    = $menu->where('id', $id)->first();
            $array = [];
            foreach ($data as $k => $v) {
                $v['selected'] = $v['id'] == $rs['parent_id'] ? 'selected' : '';
                $array[]       = $v;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
            $tree->init($array);
            $selectCategory = $tree->getTree(0, $str);
            return view('admin/system/newmenu/edit', [
                'select_category' => $selectCategory,
                'data'            => $rs
            ]);
        }

        //接收数据
        $data = $request->all();
        $data = getData($data);

        //验证数据有效性
        $validator = $this->validatePost($data);
        $titleNum  = $menu->where('title', $data['title'])->count();

        if ($titleNum > 1) {
            $validator->after(function ($validator) {
                $validator->errors()->add('title', '数据库中已经存在相同菜单名称,请重新输入');
            });
        }


        if ($validator->fails()) {
            $warnings = $validator->messages();
            $content  = $warnings->first();
            return $this->error('', $content);
        }

        $data['updated_at'] = time();

        if ((int)$data['parent_id'] == 0) {
            $data['url']   = "#";
            $data['grade'] = 0;
        } else {
            $grade         = $menu->where('id', $data['parent_id'])->first();
            $data['grade'] = $grade['grade'] + 1;
        }

        $id = $menu->where('id', $id)->update($data);
        //菜单入库判断是否正确
        if ($id) {
            //获取结构记录
            $menus = $menu->getMenuTree();
            $menuStr =  $newRloginController->getsubmenu($menus);
            //菜单信息存入session 更新菜单
            session(['menuStr' => $menuStr]);
            return $this->success(url('admin/system/newmenu/index'), '修改成功');
        } else {
            return $this->error('', '修改失败，请重新修改');
        }
    }

    /**
     * 新菜单删除
     * @param Request $request
     * @param Menu    $menu
     */
    public function delete(Request $request, Menu $menu, RoleMenuLink $menuLink, RloginController $newRloginController)
    {
        $id    = $request->input('id');
        $count = $menu->where('parent_id', $id)->count();
        if ($count > 0) {
            return $this->json_encode(0, '该菜单下有子菜单,不能删除');
        }
        //开启事务
        DB::beginTransaction();
        $delRes = $menu->where('id',$id)->delete();
        if ($delRes) {
            $data = $menuLink->get()->toArray();
            $bo   = true;
            foreach ($data as $v) {
                $res = json_decode($v['menu_id'], true);
                if (in_array((string)$id, $res, true)) {
                    $k = array_search((string)$id, $res);
                    unset($res[$k]);
                    $data1['updated_at'] = time();
                    $data1['menu_id']    = json_encode($res);
                    $upRes               = $menuLink->where('id', $v['id'])->update($data1);
                    if ($upRes) {
                        $bo = true;
                    } else {
                        $bo = false;
                    }
                } else {
                    continue;
                }
            }
            //事务判断
            if ($bo) {//成功提交
                DB::commit();
                //获取结构记录
                $menus = $menu->getMenuTree();
                $menuStr =  $newRloginController->getsubmenu($menus);
                //菜单信息存入session 更新菜单
                session(['menuStr' => $menuStr]);
                return $this->json_encode(1, '删除成功');
            } else {//失败回滚
                DB::rollback();
                return $this->json_encode(0, '删除失败');
            }
        } else {//失败回滚
            DB::rollback();
            return $this->json_encode(0, '删除失败');
        }

    }

    /**
     * 更新排序
     * @param Request $request
     * @param Menu    $menu
     * @param Result  $result
     */
    public function order(Request $request, Menu $menu, RloginController $newRloginController)
    {
        $id   = $request->input('id');
        $sort = $request->input('sort');
        if (!is_numeric($sort)) {
            return $this->json_encode(0, '更新失败');
        }
        $re = $menu->where('id', $id)->update(['sort' => $sort]);
        if ($re) {
            //获取结构记录
            $menus = $menu->getMenuTree();
            $menuStr =  $newRloginController->getsubmenu($menus);
            //菜单信息存入session 更新菜单
            session(['menuStr' => $menuStr]);
            return $this->json_encode(1, '更新成功');
        } else {
            return $this->json_encode(0, '更新失败');
        }
    }

    /**
     * 验证数据有效方法
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validatePost(array $data)
    {
        $rules   = [
            'title' => 'required',
            'url'   => 'required',
        ];
        $message = [
            'title.required' => ':attribute必须填',
            'url.required'   => ':attribute必须填',
        ];
        $field   = [
            'title' => '菜单名称',
            'name'  => '访问地址',
        ];
        return Validator::make($data, $rules, $message, $field);
    }
}
