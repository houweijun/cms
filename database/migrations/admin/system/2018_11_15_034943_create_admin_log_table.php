<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->integer('user_id')->comment("后台用户ID")->nullable();
            $table->integer('login_at')->comment("访问时间")->nullable();
            $table->string('login_ip', 255)->comment("访问IP")->nullable();
            $table->string('url', 255)->comment("访问路径")->nullable();
            $table->string('agent', 255)->comment("终端")->nullable();
            $table->integer('created_at')->comment("创建时间")->nullable();
            $table->string('created_by', 255)->nullable();
            $table->integer('updated_at')->comment("更新时间")->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->integer('deleted_at')->comment("删除时间")->nullable();
            $table->string('deleted_by', 255)->nullable();
            $table->integer('is_system')->comment("是否系统账号")->nullable();
        });

        //生成迁移修改表备注
        DB::statement("alter table `admin_log` comment'后台管理日志表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_log');
    }
}
