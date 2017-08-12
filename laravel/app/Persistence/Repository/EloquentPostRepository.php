<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.08.04.
 * Time: 18:21
 */

namespace App\Persistence\Repository;


use App\Persistence\Model\Post;
use Illuminate\Contracts\Pagination\Paginator;

class EloquentPostRepository implements PostRepository {

    /**
     * @var Post
     */
    private $model;

    public function __construct(Post $model) {
        $this->model = $model;
    }

    /**
     * Looks up posts by their categories slug.
     * @param $slug string the slugified category title
     * @param $page int page number
     * @param $size int size of the page
     * @return Paginator
     */
    public function findByCategory($slug, $page, $size)
    {
        return $this->model->query()->where(["enabled" => true])->whereHas('category', function($query) use($slug) {
            return $query->where(["name_clean" => $slug]);
        })->paginate($size, ["*"], "page", $page);
    }

    /**
     * Looks up posts by their tags slug.
     * @param $slug string the slugified tag title
     * @param $page int page number
     * @param $size int size of the page
     * @return Paginator
     */
    public function findByTag($slug, $page, $size)
    {
        return $this->model->query()->where(["enabled" => true])->whereHas('tags', function($query) use($slug) {
            return $query->where(["tag_clean" => $slug]);
        })->paginate($size, ["*"], "page", $page);
    }

    /**
     * Looks up posts by their author username.
     * @param $username string the author username
     * @param $page int page number
     * @param $size int size of the page
     * @return Paginator
     */
    public function findByAuthor($slug, $page, $size)
    {
        return $this->model->query()->where(["enabled" => true])->whereHas("author", function($query) use ($slug) {
            return $query->where(["display_name" => $slug]);
        })->paginate($size, ["*"], "page", $page);
    }

    /**
     * Returns all public posts using a paginator
     * @param $page int page number
     * @param $size int size of the page
     * @return Paginator
     */
    public function findAllPublic($page, $size)
    {
        return $this->model->query()->where(["enabled" => true])->paginate($size, ["*"], "page", $page);
    }

    /**
     * Returns all posts using a paginator
     * @param $page int page number
     * @param $size int size of the page
     * @return Paginator
     */
    public function findAll($page, $size)
    {
        return $this->model->query()->paginate($size, ["*"], "page", $page);
    }

    /**
     * Returns a post given by the slug and publish date
     * @param $slug string the slugified post title
     * @param $date string the date of the post publish
     * @return Post
     */
    public function findBySlugAndPublishedDate($slug, $date)
    {
        return $this->model->query()->where(["enabled" => true, "title_clean" => $slug, "date_published" => $date])->first();
    }

    /**
     * Returns a post by the given ID
     * @param $id int the ID
     * @return Post
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Return the most viewed posts in descending order
     * @param $limit int the number of posts returned
     * @return Post[]
     */
    public function findMostViewed($limit)
    {
        return $this->model->query()->where(["enabled" => true])->orderBy("views", "desc")->limit($limit)->get();
    }

    /**
     * Return the posts which contains the search string either in their title, category/tag name,
     * or content.
     * @param $search string the term to be searched
     * @param $page int the number of the page
     * @param $limit int the size of a page
     * @return Paginator
     */
    public function findBySearch($search, $page, $limit)
    {
        // TODO: Implement findBySearch() method.
    }

    /**
     * Saves a post instance. If it is already persisted then it performs and update.
     * @param Post $post
     * @return Post
     */
    public function save(Post $post)
    {
        // TODO: Implement save() method.
    }

    /**
     * Hard deletes a post given by its ID.
     * @param $id
     * @return void
     */
    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }
}