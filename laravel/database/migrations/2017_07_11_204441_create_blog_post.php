<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title", 144);
            $table->text('article');
            $table->string("title_clean", 144)->unique();
            $table->unsignedInteger('author_id');
            $table->timestamp("date_published");
            $table->string("banner_image");
            $table->boolean("featured");
            $table->boolean("enabled");
            $table->boolean("comments_enabled");
            $table->bigInteger("views");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_post');
    }
}
