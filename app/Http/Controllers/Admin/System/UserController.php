<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Admin\System\User;
use App\Models\Admin\System\Org;
use App\Models\Admin\System\Role;
use App\Models\Admin\System\UserRoleLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;

class UserController extends CommonController
{

    /**
     * 修改个人密码
     * @param Request $request
     * @param         $id
     */
    public function rePassWord(Request $request, $id, User $user)
    {

        if ($request->isMethod('get')) {
            return view('admin/system/user/repassword', [
                'id' => $id
            ]);
        }
        //接收数据
        $data = $request->all();
        //加密加干扰字符串处理
        $password = $data['old_password'];
        if (!empty($data['old_password'])) {
            $data['old_password'] = sha1($password . config('zhops.admin.security_key'));
        }

        //验证数据有效性
        $validator = $this->validatePost($data);
        $sqlData   = $user->where('id', $id)->first();
        //验证旧密码正确性
        if ($data['old_password'] != $sqlData['password']) {
            $validator->after(function ($validator) {
                $validator->errors()->add('password', '旧密码输入有误,请重新输入');
            });
        }

        //验证2次新密码是否一致
        if ($data['password'] != $data['re_password']) {
            $validator->after(function ($validator) {
                $validator->errors()->add('password', '新密码与确认密码不一致,请重新输入');
            });
        }

        if ($validator->fails()) {
            $warnings = $validator->messages();
            $content  = $warnings->first();
            return $this->error('', $content);
        }


        //更新数据库
        $data1 = [
            'password'   => sha1($data['password'] . config('zhops.admin.security_key')),
            'updated_at' => time()
        ];

        $res = $user->where('id', $id)->update($data1);
        if ($res) {
            return $this->success(url('admin/system/user/index'), '修改密码成功');
        } else {
            return $this->error('', '修改密码失败');
        }
    }

    //验证数据有效方法
    public function validatePost(array $data)
    {
        $rules   = [
            'old_password' => 'required',
            'password'     => 'required',
            're_password'  => 'required',

        ];
        $message = [
            'old_password.required' => ':attribute必须填',
            'password.required'     => ':attribute必须填',
            're_password.required'  => ':attribute必须填',

        ];
        $field   = [
            'old_password' => '旧密码',
            'password'     => '新密码',
            're_password'  => '确认密码',

        ];
        return Validator::make($data, $rules, $message, $field);
    }
}