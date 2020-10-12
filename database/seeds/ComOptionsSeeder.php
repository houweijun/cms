<?php

use Illuminate\Database\Seeder;
use App\Models\Common\ComOption;

class ComOptionsSeeder extends Seeder
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
        $data = split_sql($path . '/sql/com_options_insert.sql');
        //执行数据迁移
        $com_options = new ComOption();
        execute_sql($com_options, $data, 'com_options');
    }
}
