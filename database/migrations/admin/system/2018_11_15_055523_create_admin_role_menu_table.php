<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminRoleMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role_menu', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->integer('role_id')->comment("角色ID")->nullable();
            $table->text('menu_id')->comment("菜单ID")->nullable();
            $table->integer('created_at')->comment("创建时间")->nullable();
            $table->string('created_by', 255)->nullable();
            $table->integer('updated_at')->comment("更新时间")->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->integer('deleted_at')->comment("删除时间")->nullable();
            $table->string('deleted_by', 255)->nullable();
            $table->integer('is_system')->comment("是否系统账号")->nullable();
        });
        //生成迁移修改表备注
        DB::statement("alter table `admin_role_menu` comment'角色菜单关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_role_menu');
    }
}
