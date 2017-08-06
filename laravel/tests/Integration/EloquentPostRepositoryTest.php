<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.08.04.
 * Time: 18:22
 */

namespace Tests\Integration;


use App\Persistence\Model\Author;
use App\Persistence\Model\Category;
use App\Persistence\Model\Post;
use App\Persistence\Repository\EloquentPostRepository;
use App\Persistence\Repository\PostRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class EloquentPostRepositoryTest extends TestCase {

    use DatabaseMigrations;

    /**
     * @var PostRepository
     */
    private $underTest;

    public function __construct() {
        $this->underTest = new EloquentPostRepository(new Post());
    }

    /**
     * @test
     */
    public function findById_should_return_post_by_id() {
        // GIVEN
        $id = 5;
        $title = "test";
        $enabled = true;
        $this->createPost($id, $title, $enabled);
        // WHEN
        $actual = $this->underTest->findById($id);
        // THEN
        $this->assertEquals($actual->title, $title);
    }

    /**
     * @test
     */
    public function findById_should_return_post_by_id_regardless_enabled() {
        // GIVEN
        $id = 5;
        $title = "test";
        $enabled = false;
        $this->createPost($id, $title, $enabled);
        // WHEN
        $actual = $this->underTest->findById($id);
        // THEN
        $this->assertEquals($actual->title, $title);
    }

    /**
     * @test
     */
    public function findAllPublic_should_return_filled_paginator_when_got_enabled_posts() {
        // GIVEN
        $number = 20;
        $enabled = true;
        $this->createPosts($number, $enabled);
        // WHEN
        $actual = $this->underTest->findAllPublic(1,10);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), true);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 10);
    }

    /**
     * @test
     */
    public function findAllPublic_should_return_empty_paginator_when_got_no_enabled_posts() {
        // GIVEN
        $number = 20;
        $enabled = false;
        $this->createPosts($number, $enabled);
        // WHEN
        $actual = $this->underTest->findAllPublic(1,10);
        // THEN
        $this->assertEquals($actual->isEmpty(), true);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 0);
    }

    /**
     * @test
     */
    public function findAll_should_return_filled_paginator_regardless_enabled_state() {
        // GIVEN
        $number = 10;
        $this->createPosts($number, false);
        $this->createPosts($number, true);
        // WHEN
        $actual = $this->underTest->findAll(1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 20);
    }

    /**
     * @test
     */
    public function findByAuthor_should_return_enabled_posts_for_the_author() {
        // GIVEN
        $displayName = "test";
        $author = factory(Author::class)->create(["display_name" => $displayName]);
        $this->createPosts(10, true, $author);
        // WHEN
        $actual = $this->underTest->findByAuthor($displayName, 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 10);
    }

    /**
     * @test
     */
    public function findByAuthor_should_not_return_disabled_posts_for_the_author() {
        // GIVEN
        $displayName = "test";
        $author = factory(Author::class)->create(["display_name" => $displayName]);
        $this->createPosts(10, false, $author);
        // WHEN
        $actual = $this->underTest->findByAuthor($displayName, 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), true);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 0);
    }

    /**
     * @test
     */
    public function findByAuthor_should_not_return_other_authors_posts() {
        // GIVEN
        $displayName = "test";
        $author = factory(Author::class)->create(["display_name" => $displayName]);
        $this->createPosts(10, false, $author);
        // WHEN
        $actual = $this->underTest->findByAuthor("other", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), true);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 0);
    }

    /**
     * @test
     */
    public function findByCategory_should_return_enabled_posts_for_the_category() {
        // GIVEN
        $category = factory(Category::class)->create(["name_clean" => "included"]);
        $this->createPostsForCategories(20, true, [$category]);
        // WHEN
        $actual = $this->underTest->findByCategory("included", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 20);
    }

    /**
     * @test
     */
    public function findByCategory_should_return_disabled_posts_for_the_category() {
        // GIVEN
        $category = factory(Category::class)->create(["name_clean" => "included"]);
        $this->createPostsForCategories(20, false, [$category]);
        // WHEN
        $actual = $this->underTest->findByCategory("included", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), true);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 0);
    }

    /**
     * @test
     */
    public function findByCategory_should_not_return_other_categories_posts() {
        // GIVEN
        $category = factory(Category::class)->create(["name_clean" => "not included"]);
        $this->createPostsForCategories(20, true, [$category]);
        // WHEN
        $actual = $this->underTest->findByCategory("included", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), true);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 0);
    }

    /**
     * @test
     */
    public function findByCategory_should_not_interfere_with_other_categories() {
        // GIVEN
        $category = factory(Category::class)->create(["name_clean" => "included"]);
        $otherCategory = factory(Category::class)->create(["name_clean" => "not included"]);
        $this->createPostsForCategories(20, true, [$category, $otherCategory]);
        // WHEN
        $actual = $this->underTest->findByCategory("included", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 20);
    }

    /**
     * @param $id
     * @param $title
     * @param $enabled
     */
    private function createPost($id, $title, $enabled)
    {
        $post = factory(Post::class)->make(["id" => $id, "title" => $title, "enabled" => $enabled]);
        $author = factory(Author::class)->create();
        $post->author()->associate($author);
        $post->save();
    }

    /**
     * @param $number
     * @param $enabled
     */
    private function createPosts($number, $enabled, $author = null)
    {
        if ($author == null) {
            $author = factory(Author::class)->create();
        }
        factory(Post::class, $number)->make(["enabled" => $enabled])->each(function (Post $post) use ($author) {
            $post->author()->associate($author);
            $post->save();
        });
    }

    /**
     * @param $categories
     */
    private function createPostsForCategories($count, $enabled, array $categories)
    {
        $author = factory(Author::class)->create();
        factory(Post::class, $count)->make(["enabled" => $enabled])->each(function (Post $post) use ($author, $categories) {
            $post->author()->associate($author);
            $post->save();
            $post->category()->saveMany($categories);
        });
    }

}