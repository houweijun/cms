<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_channel', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->string('name', 255)->comment("渠道名称")->nullable();
            $table->integer('type')->comment("渠道类型")->nullable();
            $table->tinyInteger('status')->comment("状态")->nullable();
            $table->string('description', 255)->comment("描述")->nullable();
            $table->integer('is_system')->comment("是否系统账号")->nullable();
            $table->integer('created_at')->comment("创建时间")->nullable();
            $table->string('created_by', 255)->nullable();
            $table->integer('updated_at')->comment("更新时间")->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->integer('deleted_at')->comment("删除时间")->nullable();
            $table->string('deleted_by', 255)->nullable();
            $table->tinyInteger('parent_id')->default(0)->comment("渠道父级id")->nullable();
            $table->tinyInteger('grade')->comment("等级")->nullable();
            $table->integer('sort')->default(10000)->comment("排序");

        });
        //生成迁移修改表备注
        DB::statement("alter table `admin_channel` comment'后台渠道表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_channel');
    }
}
