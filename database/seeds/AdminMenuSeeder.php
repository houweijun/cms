<?php

use Illuminate\Database\Seeder;
use App\Models\Admin\System\Menu;

class AdminMenuSeeder extends Seeder
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
        $data = split_sql($path . '/sql/admin_menu_insert.sql');
        //执行数据迁移
        $menu = new Menu();
        execute_sql($menu, $data,'admin_menu');
    }
}
