<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDCastBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_cast_blog', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('old_id')->nullable();
            $table->integer('cast_id')->default(0);
            $table->text('title');
            $table->text('content');
            $table->text('created_at')->nullable();
            $table->text('updated_at')->nullable();
            $table->softDeletes();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->datetime('published_at')->nullable();
            $table->integer('type')->default(0);
            $table->integer('is_draft')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_cast_blog');
    }
}
