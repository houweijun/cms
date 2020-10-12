<?php

use Illuminate\Database\Seeder;
use App\Models\Admin\System\UserRoleLink;

class AdminUserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //定义数据填充数据
        $path = database_path();
        $data = split_sql($path . '/sql/admin_user_role_insert.sql');
        //执行数据迁移
        $admin_user_role = new UserRoleLink();
        execute_sql($admin_user_role, $data, 'admin_user_role');
    }
}
