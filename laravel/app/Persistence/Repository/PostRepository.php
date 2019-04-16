<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.07.30.
 * Time: 21:19
 */

namespace App\Persistence\Repository;


use App\Persistence\Model\Post;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Repository for accessing Post entities with the underlying persistence mechanism.
 */
interface PostRepository {

    /**
     * Looks up posts by their categories slug.
     * @param $slug string the slugified category title
     * @param $page int page number
     * @param $size int size of the page
     * @return Paginator
     */
    public function findByCategory($slug, $page, $size);

    /**
     * Looks up posts by their tags slug.
     * @param $slug string the slugified tag title
     * @param $page int page number
     * @param $size int size of the page
     * @return Paginator
     */
    public function findByTag($slug, $page, $size);
    /**
     * Looks up posts by their author username.
     * @param $username string the author username
     * @param $page int page number
     * @param $size int size of the page
     * @return Paginator
     */
    public function findByAuthor($slug, $page, $size);

    /**
     * Returns all public posts using a paginator
     * @param $page int page number
     * @param $size int size of the page
     * @return LengthAwarePaginator
     */
    public function findAllPublic($page, $size);

    /**
     * Returns all posts using a paginator
     * @param $page int page number
     * @param $size int size of the page
     * @return Paginator
     */
    public function findAll($page, $size);

    /**
     * Returns a post given by the slug and publish date
     * @param $slug string the slugified post title
     * @param $date string the date of the post publish
     * @return Post
     */
    public function findBySlugAndPublishedDate($slug, $date);

    /**
     * Returns a post by the given ID
     * @param $id int the ID
     * @return Post
     */
    public function findById($id);

    /**
     * Return the most viewed posts in descending order
     * @param $limit int the number of posts returned
     * @return Post[]
     */
    public function findMostViewed($limit);

    /**
     * Return the posts which contains the search string either in their title, category/tag name,
     * or content.
     * @param $search string the term to be searched
     * @param $page int the number of the page
     * @param $limit int the size of a page
     * @return Paginator
     */
    public function findBySearch($search, $page, $limit);

    /**
     * Saves a post instance. If it is already persisted then it performs and update.
     * @param Post $post
     * @return Post
     */
    public function save(Post $post);

    /**
     * Hard deletes a post given by its ID.
     * @param $id
     * @return void
     */
    public function deleteById($id);

}