<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefineForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("blog_post_to_category", function(Blueprint $table) {
           $table->foreign("category_id")->references("id")->on("blog_category");
           $table->foreign("post_id")->references("id")->on("blog_post");
        });
        Schema::table("blog_tag", function(Blueprint $table) {
            $table->foreign("post_id")->references("id")->on("blog_post");
        });
        Schema::table("blog_comment", function(Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("post_id")->references("id")->on("blog_post");
        });
        Schema::table("blog_related", function(Blueprint $table) {
            $table->foreign("blog_post_id")->references("id")->on("blog_post");
        });
        Schema::table("blog_post", function(Blueprint $table) {
            $table->foreign("author_id")->references("id")->on("blog_author");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("blog_post_to_category", function(Blueprint $table) {
            $table->dropForeign(["category_id"]);
            $table->dropForeign(["post_id"]);
        });
        Schema::table("blog_tag", function(Blueprint $table) {
            $table->dropForeign(["post_id"]);
        });
        Schema::table("blog_comment", function(Blueprint $table) {
            $table->dropForeign(["user_id"]);
            $table->dropForeign(["post_id"]);
        });
        Schema::table("blog_related", function(Blueprint $table) {
            $table->dropForeign(["blog_post_id"]);
        });
        Schema::table("blog_post", function(Blueprint $table) {
            $table->dropForeign(["author_id"]);
        });
    }
}
