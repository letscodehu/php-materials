<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.07.22.
 * Time: 11:06
 */

namespace App\Persistence\Model;


use Illuminate\Database\Eloquent\Model;

class Author extends Model {
    public $table = "blog_author";
    public $timestamps = null;

    public function posts() {
        return $this->hasMany(Post::class, "author_id", "id");
    }

}