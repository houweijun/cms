<?php

use Illuminate\Database\Seeder;
use App\Models\Admin\System\Org;

class AdminChannelSeeder extends Seeder
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
        $data = split_sql($path . '/sql/admin_channel_insert.sql');
        //执行数据迁移
        $org = new Org();
        execute_sql($org, $data, 'admin_channel');
    }
}
