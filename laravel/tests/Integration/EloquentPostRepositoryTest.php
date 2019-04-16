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
use App\Persistence\Model\Tag;
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
     * @test
     */
    public function findByTag_should_return_enabled_posts_for_tag() {
        // GIVEN
        $tag = factory(Tag::class)->create(["tag_clean" => "included"]);
        $this->createPostsForTags(20, true, [$tag]);
        // WHEN
        $actual = $this->underTest->findByTag("included", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 20);
    }

    /**
     * @test
     */
    public function findByTag_should_not_return_disabled_posts_for_tag() {
        // GIVEN
        $tag = factory(Tag::class)->create(["tag_clean" => "included"]);
        $this->createPostsForTags(20, false, [$tag]);
        // WHEN
        $actual = $this->underTest->findByTag("included", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), true);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 0);
    }

    /**
     * @test
     */
    public function findByTag_should_not_return_other_tags_posts() {
        // GIVEN
        $tag = factory(Tag::class)->create(["tag_clean" => "not included"]);
        $this->createPostsForTags(20, false, [$tag]);
        // WHEN
        $actual = $this->underTest->findByTag("included", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), true);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 0);
    }

    /**
     * @test
     */
    public function findByTag_should_not_interfere_with_other_tags() {
        // GIVEN
        $tag = factory(Tag::class)->create(["tag_clean" => "included"]);
        $notIncludedTag = factory(Tag::class)->create(["tag_clean" => "not included"]);
        $this->createPostsForTags(20, true, [$tag, $notIncludedTag]);
        // WHEN
        $actual = $this->underTest->findByTag("included", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 20);
    }

    /**
     * @test
     */
    public function findBySlugAndPublishedDate_should_return_enabled_post_by_date_and_slug() {
        // GIVEN
        $post = factory(Post::class)->make(["title_clean" => "test", "enabled" => true, "date_published" => "2017-10-11"]);
        $author = factory(Author::class)->create();
        $post->author()->associate($author);
        $post->save();
        // WHEN
        $post = $this->underTest->findBySlugAndPublishedDate("test", "2017-10-11");
        // THEN
        $this->assertEquals("test", $post->title_clean);
    }


    /**
     * @test
     */
    public function findBySlugAndPublishedDate_should_not_return_disabled_post_by_date_and_slug() {
        // GIVEN
        $post = factory(Post::class)->make(["title_clean" => "test", "enabled" => false, "date_published" => "2017-10-11"]);
        $author = factory(Author::class)->create();
        $post->author()->associate($author);
        $post->save();
        // WHEN
        $post = $this->underTest->findBySlugAndPublishedDate("test", "2017-10-11");
        // THEN
        $this->assertNull($post);
    }

    /**
     * @test
     */
    public function findMostViewed_should_return_most_viewed_enabled_posts_in_an_array() {
        // GIVEN
        $this->createPost(1, "last", true, 1 );
        $this->createPost(2, "middle", true, 5);
        $this->createPost(3, "disabled", false, 5);
        $this->createPost(4, "first", true, 10);
        // WHEN
        $posts = $this->underTest->findMostViewed(3);
        // THEN
        $this->assertEquals(10, $posts[0]->views);
        $this->assertEquals(5, $posts[1]->views);
        $this->assertEquals(1, $posts[2]->views);
    }

    /**
     * @test
     */
    public function findMostViewed_should_return_only_limited_posts() {
        // GIVEN
        $this->createPost(1, "not included", true, 0 );
        $this->createPost(2, "last", true, 1);
        $this->createPost(3, "middle", true, 5);
        $this->createPost(4, "first", true, 10);
        // WHEN
        $posts = $this->underTest->findMostViewed(3);
        // THEN
        $this->assertEquals(3, count($posts));
    }

    /**
     * @test
     */
    public function findMostViewed_should_return_a_regular_array()
    {
        // GIVEN
        // WHEN
        $posts = $this->underTest->findMostViewed(3);
        // THEN
        $this->assertFalse(is_object($posts));
        $this->assertTrue(is_array($posts));
    }

    /**
     * @test
     */
    public function deleteById_should_remove_items_by_id()
    {
        // GIVEN
        $this->createPost(1, "test", true);
        // WHEN
        $this->underTest->deleteById(1);
        // THEN
        $this->assertDatabaseMissing("blog_post", ["id" => 1]);
    }

    /**
     * @test
     */
    public function deleteById_should_not_fail_when_no_id_present()
    {
        // GIVEN
        // WHEN
        $this->underTest->deleteById(1);
        // THEN
        $this->assertDatabaseMissing("blog_post", ["id" => 1]);
    }


    /**
     * @test
     */
    public function save_should_create_new_entry_in_db_if_not_present() {
        // GIVEN
        $post = factory(Post::class)->make(["id" => 1]);
        $author = factory(Author::class)->create();
        $post->author()->associate($author);
        // WHEN
        $this->underTest->save($post);
        // THEN
        $this->assertDatabaseHas("blog_post", ["id" => 1]);
    }

    /**
     * @test
     */
    public function save_should_update_entry_in_db_if_present() {
        // GIVEN
        $this->createPost(1, "test", true);
        $post = $this->underTest->findById(1);
        $post->title = "changed";
        // WHEN
        $this->underTest->save($post);
        // THEN
        $this->assertDatabaseHas("blog_post", ["id" => 1, "title" => "changed"]);
    }

    /**
     * @test
     */
    public function save_should_return_the_persisted_entity() {
        // GIVEN
        $post = factory(Post::class)->make(["id" => 1]);
        $author = factory(Author::class)->create();
        $post->author()->associate($author);
        // WHEN
        $actual = $this->underTest->save($post);
        // THEN
        $this->assertEquals(1, $actual->id);
        $this->assertEmpty($actual->getDirty());
    }

    /**
     * @test
     */
    public function findBySearch_should_return_enabled_posts_by_matching_tag_clean() {
        // GIVEN
        $tag = factory(Tag::class)->create(["tag_clean" => "included"]);
        $this->createPostsForTags(20, true, [$tag]);
        // WHEN
        $actual = $this->underTest->findBySearch("inclu", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 20);
    }

    /**
     * @test
     */
    public function findBySearch_should_return_enabled_posts_by_matching_tag() {
        // GIVEN
        $tag = factory(Tag::class)->create(["tag" => "included"]);
        $this->createPostsForTags(20, true, [$tag]);
        // WHEN
        $actual = $this->underTest->findBySearch("inclu", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 20);
    }

    /**
     * @test
     */
    public function findBySearch_should_return_enabled_posts_by_matching_category_name() {
        // GIVEN
        $category = factory(Category::class)->create(["name" => "included"]);
        $this->createPostsForCategories(20, true, [$category]);
        // WHEN
        $actual = $this->underTest->findBySearch("inclu", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 20);
    }

    /**
     * @test
     */
    public function findBySearch_should_return_enabled_posts_by_matching_category_name_clean() {
        // GIVEN
        $category = factory(Category::class)->create(["name_clean" => "included"]);
        $this->createPostsForCategories(20, true, [$category]);
        // WHEN
        $actual = $this->underTest->findBySearch("inclu", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 20);
    }

    /**
     * @test
     */
    public function findBySearch_should_return_enabled_posts_by_matching_title() {
        // GIVEN
        $this->createPost(1, "included", true);
        $this->createPost(2, "not included", false);
        // WHEN
        $actual = $this->underTest->findBySearch("inclu", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 1);
    }

    /**
     * @test
     */
    public function findBySearch_should_return_enabled_posts_by_matching_title_clean() {
        // GIVEN
        $post = factory(Post::class)->make(["title_clean" => "included", "enabled" => true]);
        $author = factory(Author::class)->create();
        $post->author()->associate($author);
        $post->save();
        // WHEN
        $actual = $this->underTest->findBySearch("inclu", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 1);
    }

    /**
     * @test
     */
    public function findBySearch_should_return_enabled_posts_by_matching_content() {
        // GIVEN
        $post = factory(Post::class)->make(["article" => "included", "enabled" => true]);
        $author = factory(Author::class)->create();
        $post->author()->associate($author);
        $post->save();
        // WHEN
        $actual = $this->underTest->findBySearch("inclu", 1, 20);
        // THEN
        $this->assertEquals($actual->isEmpty(), false);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals(count($actual->items()), 1);
    }


    /**
     * @param $id
     * @param $title
     * @param $enabled
     */
    private function createPost($id, $title, $enabled, $views = 1)
    {
        $post = factory(Post::class)->make(["id" => $id, "title" => $title, "enabled" => $enabled, "views" => $views]);
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

    /**
     * @param $tags
     */
    private function createPostsForTags($count, $enabled, array $tags)
    {
        $author = factory(Author::class)->create();
        factory(Post::class, $count)->make(["enabled" => $enabled])->each(function (Post $post) use ($author, $tags) {
            $post->author()->associate($author);
            $post->save();
            $post->tags()->saveMany($tags);
        });
    }


}