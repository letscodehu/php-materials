<?php

namespace Tests\Integration;


use App\Persistence\Model\Author;
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
        $this->createPostEntity($id, $enabled, $title);
        // WHEN
        $actual = $this->underTest->findById(5);
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
        $this->createPostEntity($id, $enabled, $title);
        // WHEN
        $actual = $this->underTest->findById($id);
        // THEN
        $this->assertEquals($actual->title, $title);
    }

    /**
     * @test
     */
    public function findById_should_return_null_when_no_entity_found() {
        // GIVEN
        // WHEN
        $actual = $this->underTest->findById(5);
        // THEN
        $this->assertNull($actual);
    }


    /**
     * @test
     */
    public function findAllPublic_should_return_filled_paginator_when_got_enabled_posts()
    {
        // GIVEN
        $numberofPosts = 20;
        $enabled = true;
        $this->createPosts($numberofPosts, $enabled);
        // WHEN
        $actual = $this->underTest->findAllPublic(1,10);
        // THEN
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals($actual->hasPages(), true);
        $this->assertEquals(count($actual->items()), 10);
        $this->assertEquals($actual->isEmpty(), false);
    }

    /**
     * @test
     */
    public function findAllPublic_should_return_empty_paginator_when_got_no_enabled_posts()
    {
        // GIVEN
        $numberofPosts = 20;
        $enabled = false;
        $this->createPosts($numberofPosts, $enabled);
        // WHEN
        $actual = $this->underTest->findAllPublic(1,10);
        // THEN
        $this->assertEquals($actual->currentPage(), 1);
        $this->assertEquals($actual->hasPages(), false);
        $this->assertEquals(count($actual->items()), 0);
        $this->assertEquals($actual->isEmpty(), true);
    }

    private function createPostEntity($id, $enabled, $title)
    {
        $post = factory(Post::class)->make(["id" => $id, "enabled" => $enabled, "title" => $title]);
        $author = factory(Author::class)->create();
        $post->author()->associate($author);
        $post->save();
    }

    private function createPosts($numberofPosts, $enabled)
    {
        $author = factory(Author::class)->create();
        factory(Post::class, $numberofPosts)->make(["enabled" => $enabled])->each(function (Post $post) use ($author) {
            $post->author()->associate($author);
            $post->save();
        });;
    }

}
