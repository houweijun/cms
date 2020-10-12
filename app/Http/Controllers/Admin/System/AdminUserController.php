<?php

namespace App\Http\Controllers\Admin\System;

use App\Models\Admin\System\Role;
use App\Models\Admin\System\User;
use App\Models\Admin\System\UserRoleLink;
use App\Tools\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\System\Org;
use App\Tools\Tree;
use DB;
use App\Http\Controllers\Controller;

class AdminUserController extends Controller
{
  /**
   * 管理员管理 首页
   * @param Request $request
   * @param User    $user
   */
  public function index(Request $request, User $user)
  {

    $param = $request->all();

    $where = $user;


    //筛选管理员账号
    if (isset($param['username']) && !empty($param['username'])) {
      $where = $where->where('username', 'like', '%' . trim($param['username']) . '%');
    }
    //筛选权限
    if (isset($param['authority_id'])) {
      $where = $where->where('authority_id', intval($param['authority_id']));
    }

    //筛选 状态
    if (isset($param['status'])) {
      $where = $where->where('status', intval($param['status']));
    }

    $data = $where->paginate(20);

    return view('admin/system/admin_user/index', [
        'data'         => $data,
        'username'     => isset($param['username']) ? $param['username'] : '',
        'authority_id' => isset($param['authority_id']) ? $param['authority_id'] : '',
        'status'       => isset($param['status']) ? intval($param['status']) : '',
        'roleData'     => !empty($this->roleData()) ? $this->roleData()->toArray() : ''
    ]);
  }

  /**
   * 增加管理员
   * @param Request $request
   * @param User    $user
   */
  public function add(Request $request, User $user, UserRoleLink $roleLink ,Org $org, Tree $tree)
  {
    //如果是get请求 渲染页面
    if ($request->isMethod('get')) {

      $orgData = $org->select('id', 'name', 'parent_id')->orderBy('sort', 'asc')->get()->toArray();
      $array   = [];

      foreach ($orgData as $k => $v) {
        $v['selected'] = $v['id'] == old('org_id') ? 'selected' : '';
        $array[]       = $v;
      }
      $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
      $tree->init($array);

      if (!empty(old('parent_id'))) {
        $selectOrg = $tree->getTree(old('parent_id'), $str);
      } else {
        $selectOrg = $tree->getTree(0, $str);
      }
      return view('admin/system/admin_user/add', [
          'roleData' => !empty($this->roleData()) ? $this->roleData()->toArray() : '',
          'select_org' => $selectOrg
      ]);
    }

    //接收数据
    $data = $request->all();

    //验证数据有效性
    $validator = $this->validatePost($data);

    //查询数据库 活动开启数量
    $usernameCount = $user->where('username', $data['username'])->count();

    if ($usernameCount >= 1) {
      $validator->after(function ($validator) {
        $validator->errors()->add('username', '已经存在,请重新输入账号');
      });
    }

    if ($data['password'] != $data['repassword']) {
      $validator->after(function ($validator) {
        $validator->errors()->add('repassword', '管理员2次密码，不一致');
      });
    }

    if ($validator->fails()) {
      $warnings = $validator->messages();
      $content  = $warnings->first();
      return $this->error('', $content);
    }

    $data1 = [
        'username'     => $data['username'],
        'password'     => sha1($data['password'] . config('zhops.admin.security_key')), //密码加密
        'nickname'     => $data['username'],
        'authority_id' => $data['authority_id'],
        'description'  => $data['description'],
        'org_id'       => $data['org_id'],
        'is_system'       => $data['is_system'],
        'register_at'  => time(),
        'status'       => 1,
        'is_banned'    => 1,
        'is_gag'       => 1,
        'created_at'   => time(),
    ];


    //开启事务
    DB::beginTransaction();
    //数据入库
    $id = $user->insertGetId($data1);
    if ($id) {
      $data2 = [
          'user_id'    => $id,
          'role_id'    => json_encode([$data['authority_id']]),
          'created_at' => time()
      ];
      $res   = $roleLink->insertGetId($data2);
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
      return $this->success(url('admin/system/adminuserindex'), '添加成功');
    } else {
      return $this->error('', '添加失败，请重新添加');
    }

  }

  /**
   * 编辑管理员
   * @param Request $request
   * @param User    $user
   */
  public function edit($id, Request $request, User $user, UserRoleLink $roleLink,Org $org, Tree $tree)
  {
    //如果是get请求 渲染页面
    if ($request->isMethod('get')) {
      $data = Db::table('admin_user')->where('id', $id)->first();

      $orgData = $org->select('id', 'name', 'parent_id')->orderBy('sort', 'asc')->get()->toArray();
      $array   = [];

      foreach ($orgData as $k => $v) {
        $v['selected'] = $v['id'] == $data->org_id ? 'selected' : '';
        $array[]       = $v;
      }
      $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
      $tree->init($array);
      $selectOrg = $tree->getTree(0, $str);
      return view('admin/system/admin_user/edit', [
          'roleData' => !empty($this->roleData()) ? $this->roleData()->toArray() : '',
          'data'     => !empty($data) ? $data : '',
          'select_org' => $selectOrg,
      ]);
    }

    //接收数据
    $data = $request->all();


    //修改密码为空
    if (!empty($data['password'])) {
      if ($data['password'] != $data['repassword']) {
        //验证数据有效性
        $validator = $this->validatePost($data);
        $validator->after(function ($validator) {
          $validator->errors()->add('repassword', '管理员2次密码，不一致');
        });
      }
      $data1 = [
          'password'     => sha1($data['password'] . config('zhops.admin.security_key')), //密码加密
          'authority_id' => $data['authority_id'],
          'description'  => $data['description'],
          'org_id'       => $data['org_id'],
          'updated_at'   => time(),
      ];
      unset($data['oldpassword']);
    } else {
      $data['password'] = $data['oldpassword'];
      $data['repassword'] = $data['oldpassword'];
      unset($data['oldpassword']);
      $data1 = [
          'password'     => $data['password'], //密码加密
          'authority_id' => $data['authority_id'],
          'description'  => $data['description'],
          'org_id'       => $data['org_id'],
          'updated_at'   => time(),
      ];
    }
    $data1['is_system'] = $data['is_system'];
    //验证数据有效性
    $validator = $this->validatePost($data);

    if($id ==1 && $data['status'] == 2 && $data['is_system'] ==2){
      $validator->after(function ($validator) {
        $validator->errors()->add('status', '系统管理员,登录平台、状态不能修改');
      });
    }
    if ($validator->fails()) {
      $warnings = $validator->messages();
      $content  = $warnings->first();
      return $this->error('', $content);
    }


    //开启事务
    DB::beginTransaction();
    //数据入库
    $res = $user->where('id', $id)->update($data1);
    if ($res) {
      $data2 = [
          'user_id'    => $id,
          'role_id'    => json_encode([$data['authority_id']]),
          'updated_at' => time()
      ];

      $res1 = $roleLink->where('user_id', $id)->update($data2);
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
      return $this->success(url('admin/system/adminuserindex'), '修改成功');
    } else {
      return $this->error('', '修改失败，请重新修改');
    }

  }

  /**
   * 更新开关状态
   * @param      $id
   * @param      $status
   * @param User $user
   * @return AdminUserController
   */
  public function status($id, $status, User $user)
  {
    $status = $status == 1 ? 2 : 1;
//        if($id ==1 && $status == 2){
//            return $this->error(url('admin/system/adminuserindex'), '系统管理员,状态不能修改');
//        }
    $re     = $user->where('id', $id)->update(['status' => $status]);
    if ($re) {
      return $this->success(url('admin/system/adminuserindex'), '修改成功');
    }
    return $this->error(url('admin/system/adminuserindex'), '修改失败');
  }

  /**
   * ajax 删除
   * @param Request $request
   * @param User    $user
   * @param Result  $result
   */
  public function delete(Request $request, User $user, Result $result, UserRoleLink $roleLink)
  {
    $data = $request->all();
    //开启事务
    DB::beginTransaction();
    //数据入库
    $res = $user->where('id', $data['id'])->update(['deleted_at' => time(), 'status' => 2]);
    if ($res) {

      $res1 = $roleLink->where('user_id', $data['id'])->update(['deleted_at' => time()]);
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
   * 获取角色选项
   * @return mixed
   */
  public function roleData()
  {
    $roleData = Role::select('id', 'name')->get();
    return $roleData;
  }

  public function validatePost(array $data)
  {
    $rules = [
        'username' => 'required',
        'password' => 'required|regex:/(?=.*[a-z])(?=.*\d)(?=.*[#@!~%^&*_-])[a-z\d#@!~%^&*_-]{6,}/i',
        'repassword' => 'required|regex:/(?=.*[a-z])(?=.*\d)(?=.*[#@!~%^&*_-])[a-z\d#@!~%^&*_-]{6,}/i',
    ];
    $message = [
        'username.required' => ':attribute必须填',
        'password.required' => ':attribute必须填',
        'password.regex' => ':attribute至少请输入6位且字母、数字、破折号-以及下划线特殊组合(?=.*[#@!~%^&*_-])',
        'repassword.required' => ':attribute必须填',
        'repassword.regex' => ':attribute至少请输入6位且字母、数字、破折号-以及下划线组合(?=.*[#@!~%^&*_-])',
    ];
    $field = [
        'username' => '管理员账号',
        'password' => '管理员密码',
        'repassword' => '确认密码',
    ];
    return Validator::make($data, $rules, $message, $field);
  }
}
