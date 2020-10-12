<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //填充初始化菜单数据
        $this->call(AdminMenuSeeder::class);
        //填充后台管理员数据
        $this->call(AdminUserSeeder::class);
        //填充后台用户角色关联表数据
        $this->call(AdminUserRoleSeeder::class);
        //填充后台角色表数据
        $this->call(AdminRoleSeeder::class);
        //填充后台角色菜单关联表数据
        $this->call(AdminRoleMenuSeeder::class);
        //填充后台渠道表数据
        $this->call(AdminChannelSeeder::class);
        //填充后台通用参数数据
        $this->call(ComOptionsSeeder::class);
    }
}
