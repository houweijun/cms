<?php

use Illuminate\Database\Seeder;
use App\Models\Admin\System\User;

class AdminUserSeeder extends Seeder
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
        $data = split_sql($path . '/sql/admin_user_insert.sql');
        //执行数据迁移
        $user = new User();
        execute_sql($user, $data, 'admin_user');
    }
}
