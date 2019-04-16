<?php

namespace App\Persistence\Model;


use Illuminate\Database\Eloquent\Model;

class Author extends Model {
    public $table = "blog_author";
    public $timestamps = null;

    public function posts() {
        return $this->hasMany(Post::class, "author_id", "id");
    }

    public function getDisplayName() {
        return $this->display_name;
    }

}