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
        return $this->belongsTo(Post::class, "post_id", "id", Tag::class);
    }

}