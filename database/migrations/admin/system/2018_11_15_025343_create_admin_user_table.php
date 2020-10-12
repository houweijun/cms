<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->string('username', 255)->comment("用户名")->nullable();
            $table->string('password', 255)->comment("密码")->nullable();
            $table->string('nickname', 255)->comment("昵称")->nullable();
            $table->integer('default_role')->comment("默认角色id")->nullable();
            $table->integer('org_id')->comment("组织id")->nullable();
            $table->integer('register_at')->comment("注册时间")->nullable();
            $table->string('register_ip',255)->comment("注册IP")->nullable();
            $table->string('logined_app',255)->comment("登录过的app")->nullable();
            $table->integer('last_login_at')->comment("最近登录时间")->nullable();
            $table->string('last_login_ip',255)->comment("最近登录IP")->nullable();
            $table->tinyInteger('status')->comment("状态")->nullable();
            $table->integer('is_banned')->comment("是否封禁")->nullable();
            $table->integer('is_gag')->comment("是否禁言")->nullable();
            $table->string('description', 255)->comment("描述")->nullable();
            $table->integer('is_system')->comment("是否系统账号")->nullable();
            $table->integer('created_at')->comment("创建时间")->nullable();
            $table->string('created_by', 255)->nullable();
            $table->integer('updated_at')->comment("更新时间")->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->integer('deleted_at')->comment("删除时间")->nullable();
            $table->string('deleted_by', 255)->nullable();
            $table->integer('authority_id')->comment("权限管理id")->nullable();
        });

        //生成迁移修改表备注
        DB::statement("alter table `admin_user` comment'后台用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user');
    }
}
