<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDShopBlogContsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_shop_blog_conts', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id')->defalut(0)->nullable();
            $table->text('conts')->nullable();
            $table->text('name')->nullable();
            $table->text('delete_flg')->nullable();
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
        Schema::dropIfExists('d_shop_blog_conts');
    }
}
