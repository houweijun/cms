<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menu', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->comment("id主键");
            $table->string('title', 255)->charset('utf8')->comment("菜单名称");
            $table->string('url', 255)->charset('utf8')->comment("路径");
            $table->string('iconclass', 255)->charset('utf8')->comment("样式图标")->nullable();
            $table->tinyInteger('status')->comment("状态")->nullable();
            $table->string('description', 255)->charset('utf8')->comment("描述")->nullable();
            $table->integer('parent_id')->comment("菜单父Id");
            $table->integer('grade')->comment("菜单等级");
            $table->integer('created_at')->comment("创建时间")->nullable();
            $table->string('created_by', 255)->charset('utf8')->nullable();
            $table->integer('updated_at')->comment("更新时间")->nullable();
            $table->string('updated_by', 255)->charset('utf8')->nullable();
            $table->integer('deleted_at')->comment("删除时间")->nullable();
            $table->string('deleted_by', 255)->charset('utf8')->nullable();
            $table->integer('is_system')->nullable();
            $table->integer('sort')->default(10000)->comment("菜单排序");
            $table->tinyInteger('is_sub')->default(1)->comment("是否是子菜单 1:否 2:是");
        });
        //生成迁移修改表备注
        DB::statement("alter table `admin_menu` comment'菜单表'");
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_menu');
    }
}
