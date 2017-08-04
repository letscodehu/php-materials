<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.07.22.
 * Time: 11:10
 */

namespace App\Persistence\Model;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    const CREATED_AT = "date";
    const UPDATED_AT = null;

    protected $table = "blog_comment";

    public function parent() {
        return $this->belongsTo(Comment::class, "is_reply_to", "id", Comment::class);
    }

    public function post() {
        return $this->belongsTo(Post::class, "post_id", "id", Comment::class);
    }

    public function user() {
        return $this->belongsTo(User::class, "user_id", "id", Comment::class);
    }

}