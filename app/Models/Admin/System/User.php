<?php

namespace App\Models\Admin\System;

use App\Models\Common\ComModel;
use App\Models\Common\ComOption;
class User extends ComModel
{
    protected $table      = "admin_user";
    protected $dateFormat = 'U';
    protected $guarded    = ['updated_at','created_at'];

    /**
     * 状态访问器
     * @param $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        if ($value != null){
            $status = self::getStatus();
            return $status[$value];
        }else{
            return '未知';
        }

    }

    /**
     * 组织访问器
     * @param $value
     * @return string
     */
    public function getOrgIdAttribute($value){
        if($value != null){
            $org = Org::where('id',$value)->first();
            return $org->name;
        }else{
            return "未知";
        }
    }

    /**
     * 默认角色访问器
     * @param $value
     * @return string
     */
    public function getRoleAttribute($value)
    {

        if ($value != null) {
            $role = Role::where('id',$value)->first();
            return $role->name;
        }else{
            return "未知";
        }

    }

    /**
     * 是否封禁访问器
     * @param $value
     * @return string
     */
    public function getBannedAttribute($value)
    {
        if ($value != null){
            $banned = self::getBanned();
            return $banned[$value];
        }else{
            return '未知';
        }




    }

    /**
     * 是否禁言访问器
     * @param $value
     * @return string
     */
    public function getGagAttribute($value)
    {
        if ($value != null){
            $gag = self::getBanned();
            return $gag[$value];
        }else{
            return '未知';
        }

    }

    /**
     * 获取用户状态数组
     * @return mixed
     */
    public static function getStatus(){
        $obj    = ComOption::where('name','user_status')->first();
        $status = $obj->options;
        return $status;
    }

    /**
     * 获取是否禁言数组
     * @return mixed
     */
    public static function getBanned(){
        $obj           = ComOption::where('name','user_isbanned')->first();
        $isbanned_list = $obj->options;
        return $isbanned_list;
    }

    /**
     * 获取是否封禁
     * @return mixed
     */
    public static function getGag(){
        $obj        = ComOption::where('name','user_isgag')->first();
        $isgag_list = $obj->options;
        return $isgag_list;
    }

}