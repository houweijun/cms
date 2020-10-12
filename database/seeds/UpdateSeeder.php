<?php

use Illuminate\Database\Seeder;

class UpdateSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //定义数据填充数据-文章分类管理
    $path = database_path();
    $file = $path . '/sql/update/update_category.sql';
    update_execute_sql($file, '-文章分类菜单');

    //文章分类数据填充初始化数据
    $file = $path . '/sql/update/insert_category.sql';
    update_execute_sql($file, '-文章分类数据填充');
  }
}
