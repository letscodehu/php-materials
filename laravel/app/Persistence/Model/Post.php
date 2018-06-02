<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.07.22.
 * Time: 11:02
 */

namespace App\Persistence\Model;


use Illuminate\Database\Eloquent\Model;

class Post extends Model {

    public $table = "blog_post";
    public $timestamps = null;

    public function comments() {
        return $this->hasMany(Comment::class, "post_id", "id");
    }

    public function author() {
        return $this->belongsTo(Author::class, "author_id", "id", Post::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, "blog_post_to_tag", "post_id", "tag_id", Post::class);
    }

    public function related() {
        return $this->belongsToMany(Post::class, "blog_related", "blog_post_id", "blog_related_post_id", Post::class);
    }

    public function category() {
        return $this->belongsToMany(Category::class, "blog_post_to_category", "post_id", "category_id", Post::class);
    }

    public function getTitle() {
        return $this->title;
    }

    public function getTitleClean() {
        return $this->title_clean;
    }

    public function getArticle() {
        return $this->article;
    }

    public function getDatePublished()
    {
        return $this->date_published;
    }

}