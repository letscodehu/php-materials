<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.07.22.
 * Time: 11:08
 */

namespace App\Persistence\Model;


use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

    public $table = "blog_tag";
    public $timestamps = null;

    public function posts() {
        return $this->belongsToMany(Post::class,"blog_post_to_tag", "tag_id", "post_id", Tag::class);
    }

    public function getPostsCount() {
        return $this->posts_count;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function getTitle() {
        return $this->title;
    }

}