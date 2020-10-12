<?php

use Illuminate\Database\Seeder;
use App\Models\Admin\System\RoleMenuLink;

class AdminRoleMenuSeeder extends Seeder
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
        $data = split_sql($path . '/sql/admin_role_menu_insert.sql');
        //执行数据迁移
        $admin_role_menu = new RoleMenuLink();
        execute_sql($admin_role_menu, $data, 'admin_role_menu');
    }
}
