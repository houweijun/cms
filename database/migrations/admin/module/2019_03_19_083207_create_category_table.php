<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->charset = 'utf8';
          $table->collation = 'utf8_general_ci';
          $table->increments('id');
          $table->bigInteger('parent_id')->comment("父id")->nullable();
          $table->string('name', 255)->comment('文章分类名称')->nullable();
          $table->string('description', 255)->charset('utf8')->comment("文章分类描述")->nullable();
          $table->integer('sort')->default(10000)->comment("文章分类排序");
          $table->integer('created_at')->comment("创建时间")->nullable();
          $table->integer('updated_at')->comment("更新时间")->nullable();
          $table->integer('deleted_at')->comment("删除时间")->nullable();
        });

      //生成迁移修改表备注
      DB::statement("alter table `category` comment'文章分类'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category');
    }
}
