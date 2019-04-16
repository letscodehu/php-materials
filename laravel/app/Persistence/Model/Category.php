<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.07.22.
 * Time: 11:07
 */

namespace App\Persistence\Model;


use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    public $table = "blog_category";
    const UPDATED_AT = null;

    public function posts() {
        return $this->belongsToMany(Post::class, "blog_post_to_category", "category_id", "post_id", Category::class);
    }

}