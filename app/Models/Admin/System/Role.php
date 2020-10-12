<?php

namespace App\Models\Admin\System;

use App\Models\Common\ComModel;
use App\Models\Common\ComOption;
class Role extends ComModel
{
    protected $table = "admin_role";
    protected $dateFormat = 'U';
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * 状态访问器
     * @return mixed
     */
    public static function getStatus()
    {
        $obj = ComOption::where('name', 'role_status')->first();
        $status = $obj->options;
        return $status;
    }

    public function getStatusAttribute($value)
    {
        if ($value != null) {
            $obj = ComOption::where('name', 'role_status')->first();
            $status = $obj->options;
            return $status[$value];
        } else {
            return "未知";
        }


    }

}