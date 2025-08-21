<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDShopManagerBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_shop_manager_blog', function (Blueprint $table) {
            $table->id();
            $table->text('old_id')->nullable();
            $table->integer('site_id')->defalut(0)->nullable();
            $table->integer('old_kiji_id')->defalut(0)->nullable();
            // $table->integer('old_shop_id')->defalut(0)->nullable();
            $table->text('mail')->nullable();
            $table->datetime('published_at')->nullable();
            $table->text('title')->nullable();
            $table->text('content')->nullable();
            $table->text('content2')->nullable();
            $table->text('content3')->nullable();
            $table->text('mail2')->nullable();
            $table->text('image')->nullable();
            $table->text('image_direction')->nullable();
            $table->text('site_name')->nullable();
            $table->text('image_directory')->nullable();
            $table->text('old_category')->nullable();
            $table->text('category_name')->nullable();
            $table->integer('delete_flg')->nullable();
            $table->integer('updated_at');
            $table->integer('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_shop_manager_blog');
    }
}
