<?php

namespace App\Models\Admin\System;

use App\Models\Common\ComModel;
use App\Models\Common\ComOption;

class Org extends ComModel
{
    protected $table = 'admin_channel';
    protected $dateFormat = 'U';
    protected $guarded = ['updated_at', 'created_at'];
    /**
     * 状态访问器
     * @param $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        if ($value != null){
            $status=self::getOrgStatus();
            return $status[$value];
        }else{
            return "未知";
        }
    }

    /**
     * 类型访问器
     * @param $value
     * @return string
     */
    public function getTypeAttribute($value)
    {
        if ($value != null) {
            $type = self::getType();
            return $type[$value];
        }else{
            return "未知";
        }
    }

    /**
     * 获取组织类型数组
     * @return mixed
     */
    public static function getType(){
        $obj=ComOption::where('name','org_type')->first();
        $type_list=$obj->options;
        return $type_list;
    }

    /**
     * 获取组织状态数组
     * @return mixed
     */
    public static function getOrgStatus(){
        $obj=ComOption::where('name','default_status')->first();
        $status=$obj->options;
        return $status;
    }

    /**
     * 组织关联用户ok
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\Admin\system\User','org_id','id');
    }

}
