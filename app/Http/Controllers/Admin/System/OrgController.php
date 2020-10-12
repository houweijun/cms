<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Admin\System\Org;
use App\Models\Admin\System\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Bootstrap\Common\Tree;
use DB;

class OrgController extends CommonController
{
    /**
     * 组织列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Org $org, Tree $tree)
    {
        $status_list = Org::getOrgStatus();

        $param = $request->all();
        $where = $org;
        //搜索词、状态不为空
        if ((isset($param['name']) && !empty($param['name'])) | (isset($param['status']) && !empty($param['status']))) {
            //搜索词不为空
            if (isset($param['name']) && !empty($param['name'])) {
                $where = $where->where('name', 'like', '%' . trim($param['name']) . '%');
            }

            //状态不为空
            if (isset($param['status']) && !empty($param['status'])) {
                $where = $where->where('status', '=', (int)$param['status']);
            }
            $orgData = $where->orderBy('sort', 'asc')->paginate(20);

        } else { //搜索词为空   显示目录树
            $data       = $where->orderBy('sort', 'asc')->get()->toArray();
            $tree->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';


            foreach ($data as $key => $value) {
                $data[$key]['parent_id_node'] = ($value['parent_id']) ? ' class="child-of-node-' . $value['parent_id'] . '"' : '';
                $data[$key]['style']          = empty($value['parent_id']) ? '' : 'display:none;';
                $data[$key]['created_at']     = date('Y-m-d H:i:s');
                $data[$key]['add_submenu']    = $value['grade'] == 0 ? '<a class="btn btn-outline btn-primary mr5" href="' . url('admin/system/org/add', [$value['id']]) . '"><i class="fa fa-plus-square-o"></i>添加子渠道</a>' : '';
                $data[$key]['edit']           = '<a class="btn btn-outline btn-warning mr5" href="' . url('admin/system/org/edit', [$value['id']]) . '"><i class="fa fa-paste"></i>编辑</a>';
                $data[$key]['delete']         = '<a class="btn btn-outline btn-danger mr5" href="javascript:;"
                           onclick="return checkDel(\'删除渠道\',\'你确定要删除渠道' . $value['name'] . '吗?\',\'/admin/system/org/del\',this)"
                           data-id="' . $value['id'] . '">
                           <i class="fa fa-trash-o fa-lg"></i>删除
                        </a>';
                $data[$key]['order']          = '<input name="sort" type="text" size="3" value="' . $value['sort'] . '"
                                     class="input input-order" data-id="' . $value['id'] . '" onchange="listOrder(this,\'/admin/system/org/order\')" />';
                $data[$key]['status']         = $data[$key]['status'] == '激活' ? '<span class="btn btn-outline btn-primary">激活</span>' : '<span class="btn btn-outline btn-danger">注销</span>';
            }

            $tree->init($data);

            //排序权限判别
            if (roleShow('admin/system/orgorder')) {
                $str1 = "<td style='padding-left:20px'>\$order</td>";
            } else {
                $str1 = '';
            }

            //操作权限判别
            if (roleShow('admin/system/org/add') || roleShow('admin/system/org/edit') || roleShow('admin/system/org/del')) {
                //菜单添加按钮
                if (roleShow('admin/system/org/add')) {
                    $str3 = "\$add_submenu";
                } else {
                    $str3 = '';
                }
                //菜单编辑按钮
                if (roleShow('admin/system/org/edit')) {
                    $str4 = "\$edit";
                } else {
                    $str4 = '';
                }
                //菜单删除按钮
                if (roleShow('admin/system/org/del')) {
                    $str5 = "\$delete";
                } else {
                    $str5 = '';
                }
                $str2 = "<td>" . $str3 . $str4 . $str5 . "</td>";
            } else {
                $str2 = '';
            }
            //菜单组合
            $str = "<tr id='node-\$id' \$parent_id_node style='\$style'>" .
                $str1
                . "<td>\$id</td>
                        <td>\$spacer\$name</td>
                        <td>\$status</td>
                        <td>\$description</td>
                        <td>\$created_at</td>
                        " . $str2 . "
                    </tr>";

            $orgData = $tree->getTree(0, $str);
        }

        return view('admin.system.org.index', [
            'name'        => isset($param['name']) ? $param['name'] : '',
            'status'      => isset($param['status']) ? $param['status'] : '',
            'org_data'    => $orgData,
            'status_list' => $status_list,
        ]);
    }

    /**
     * 新增渠道
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orgAdd(Request $request, Org $org, $parentId = '0', Tree $tree)
    {
        if ($request->isMethod('get')) {
            $status_list = Org::getOrgStatus();
            $data        = $org->where([
                ['parent_id', '=', 0],
                ['status', '=', 1],
            ])->orderBy('sort', 'asc')->get()->toArray();
            $array       = [];
            foreach ($data as $k => $v) {
                $v['selected'] = $v['id'] == $parentId ? 'selected' : '';
                $array[]       = $v;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($array);
            $selectOrg = $tree->getTree(0, $str);
            return view('admin/system/org/add', [
                'status_list' => $status_list,
                'select_org'  => $selectOrg
            ]);
        }

        //接收数据
        $data               = $request->all();
        $data               = getData($data);
        $data['created_at'] = time();
        if ((int)$data['parent_id'] == 0) {
            $data['grade'] = 0;
        } else {
            $grade         = $org->where('id', $data['parent_id'])->first();
            $data['grade'] = $grade['grade'] + 1;
        }

        //验证数据有效性
        $validator = $this->validatePost($data);
        $nameNum   = $org->where('name', $data['name'])->count();
        if ($nameNum >= 1) {
            $validator->after(function ($validator) {
                $validator->errors()->add('name', '数据库中已经存在相同渠道名称,请重新输入');
            });
        }

        if ($validator->fails()) {
            $warnings = $validator->messages();
            $content  = $warnings->first();
            return $this->error('', $content);
        }

        $id = $org->insertGetId($data);
        //菜单入库判断是否正确
        if ($id) {
            return $this->success(url('admin/system/org/index'), '添加成功', 1);
        } else {
            return $this->error('', '添加失败，请重新添加');
        }
    }

    /**
     * 修改渠道
     * @param Request $request
     * @param         $id  int 组织id
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orgEdit(Request $request, Org $org, $id, Tree $tree)
    {
        if ($request->isMethod('get')) {
            $status_list = Org::getOrgStatus();
            $rs          = $org->where('id', $id)->first();
            $data        = $org->where([
                ['parent_id', '=', 0],
                ['status', '=', 1]
            ])->orderBy('sort', 'asc')->get()->toArray();
            $array       = [];
            foreach ($data as $k => $v) {
                $v['selected'] = $v['id'] == $rs['parent_id'] ? 'selected' : '';
                $array[]       = $v;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($array);
            $selectOrg = $tree->getTree(0, $str);
            return view('admin/system/org/edit', [
                'status_list' => $status_list,
                'select_org'  => $selectOrg,
                'org'         => $rs
            ]);
        }

        //接收数据
        $data               = $request->all();
        $data               = getData($data);
        $data['updated_at'] = time();
        if ((int)$data['parent_id'] == 0) {
            $data['grade'] = 0;
        } else {
            $grade         = $org->where('id', $data['parent_id'])->first();
            $data['grade'] = $grade['grade'] + 1;
        }

        //验证数据有效性
        $validator = $this->validatePost($data);
        $nameNum   = $org->where('name', $data['name'])->count();
        if ($nameNum > 1) {
            $validator->after(function ($validator) {
                $validator->errors()->add('name', '数据库中已经存在相同渠道名称,请重新输入');
            });
        }

        if ($validator->fails()) {
            $warnings = $validator->messages();
            $content  = $warnings->first();
            return $this->error('', $content);
        }

        $id = $org->where('id', $id)->update($data);
        //菜单入库判断是否正确
        if ($id) {
            return $this->success(url('admin/system/org/index'), '修改成功');
        } else {
            return $this->error('', '修改失败，请重新修改');
        }
    }


    /**
     * 删除组织
     * @param $id  组织id
     * @return $this
     */
    public function orgDel(Request $request, Org $org)
    {

        $id   = $request->input('id');
        $data = $org->select(DB::Raw('admin_org.*,admin_user.org_id'))
            ->LeftJoin('admin_user', 'admin_user.org_id', 'admin_org.id')
            ->where('admin_org.id', $id)
            ->first();

        if (empty($data->org_id)) {
            return $this->json_encode(0, '里面用相关联的关系，请解除关联关系后，再删除');
        }

        $count = $org->where('parent_id', $id)->count();
        if ($count > 0) {
            return $this->json_encode(0, '该渠道下有子渠道,不能删除');
        }

        $delRes = $org->where('id')->delete();
        if ($delRes) {
            return $this->json_encode(1, '删除成功');
        } else {
            return $this->json_encode(0, '删除失败');
        }

    }

    /**
     * 更新排序
     * @param Request $request
     * @param Org     $org
     * @param Result  $result
     */
    public function order(Request $request, Org $org)
    {
        $id   = $request->input('id');
        $sort = $request->input('sort');
        if (!is_numeric($sort)) {
            return $this->json_encode(0, '更新失败');
        }
        $re = $org->where('id', $id)->update(['sort' => $sort]);
        if ($re) {
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
            'name'   => 'required',
            'status' => 'required',
        ];
        $message = [
            'name.required'   => ':attribute必须填',
            'status.required' => ':attribute必填'
        ];
        $field   = [
            'name'   => '菜单名称',
            'status' => '状态',
        ];
        return Validator::make($data, $rules, $message, $field);
    }

}