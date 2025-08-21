<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDCastBlogImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_cast_blog_image', function (Blueprint $table) {
            $table->id();
            $table->integer('article_id')->default(0);
            $table->string('image_url');
            $table->string('orientation')->nullable();
            $table->text('created_at')->nullable();
            $table->text('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_cast_blog_image');
    }
}
