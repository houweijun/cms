<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateComOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('com_options', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->string('name', 255)->comment("参数名称")->nullable();
            $table->text('options')->comment("配置数组")->nullable();
            $table->string('description', 255)->comment("描述")->nullable();
            $table->integer('created_at')->comment("创建时间")->nullable();
            $table->string('created_by', 255)->nullable();
            $table->integer('updated_at')->comment("更新时间")->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->integer('deleted_at')->comment("删除时间")->nullable();
            $table->string('deleted_by', 255)->nullable();
            $table->integer('is_system')->comment("是否系统账号")->nullable();
        });
        //生成迁移修改表备注
        DB::statement("alter table `com_options` comment'通用参数数组表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('com_options');
    }
}
