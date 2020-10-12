<?php

namespace App\Models\Admin\System;

use App\Models\Common\ComModel;
class UserRoleLink extends ComModel
{
    protected $table      = 'admin_user_role';
    protected $dateFormat = 'U';
    protected $guarded    = ['created_at', 'updated_at'];
}