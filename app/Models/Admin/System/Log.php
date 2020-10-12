<?php

namespace App\Models\Admin\System;

use App\Models\Common\ComModel;

class Log extends ComModel
{
    protected $table      = 'admin_log';
    protected $dateFormat = 'U';
    protected $guarded    = ['updated_at','created_at'];

    /**
     * 用户访问器
     * @param $value
     * @return string
     */
    public function getUserIdAttribute($value)
    {
        if($value!=null){
            $user=User::where('id',$value)->first();
            return $user['username'];
        }else{
            return "未知";
        }
    }

    /**
     * 获取用户列表
     * @return mixed
     */
    static  public function getUserList(){
        return User::where('id','>=',1)->get(['id','username','nickname']);
    }

}