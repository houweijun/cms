<?php

namespace App\Models\Admin\System;

use App\Models\Common\ComModel;

class RoleMenuLink extends ComModel
{
    protected $table      = "admin_role_menu";
    protected $dateFormat = 'U';
    protected $guarded    = ['created_at', 'updated_at'];
}