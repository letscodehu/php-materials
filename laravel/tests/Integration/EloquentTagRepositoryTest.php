<?php

namespace App\Persistence\Repository;

use App\Persistence\Model\Author;
use App\Persistence\Model\Post;
use App\Persistence\Model\Tag;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Tests\TestCase;

class EloquentTagRepositoryTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @var EloquentTagRepository
     */
    private $underTest;

    /**
     * EloquentTagRepositoryTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->underTest = new EloquentTagRepository(new Tag());
    }

    /**
     * @test
     */
    public function test_it_should_contain_the_related_post_count()
    {
        // GIVEN
        $count = 5;
        $tag = factory(Tag::class)->create();
        $this->createPostsForTags($count, true, [$tag]);
        // WHEN
        /** @var Collection $actual */
        $actual = $this->underTest->getForTagCloud();
        // THEN
        $this->assertEquals($count, $actual->get(0)->posts_count);
    }

    /**
     * @test
     */
    public function test_it_should_contain_zero_when_no_posts_available()
    {
        // GIVEN
        $count = 0;
        factory(Tag::class)->create();
        // WHEN
        /** @var Collection $actual */
        $actual = $this->underTest->getForTagCloud();
        // THEN
        $this->assertEquals($count, $actual->get(0)->posts_count);
    }

    /**
     * @test
     */
    public function test_it_should_not_count_the_disabled_posts()
    {
        // GIVEN
        $count = 5;
        $tag = factory(Tag::class)->create();
        $this->createPostsForTags($count, false, [$tag]);
        // WHEN
        /** @var Collection $actual */
        $actual = $this->underTest->getForTagCloud();
        // THEN
        $this->assertEquals(0, $actual->get(0)->posts_count);
    }

    /**
     * @test
     */
    public function test_it_should_return_twenty_tags_at_most()
    {
        // GIVEN
        factory(Tag::class, 25)->create();
        // WHEN
        /** @var Collection $actual */
        $actual = $this->underTest->getForTagCloud();
        // THEN
        $this->assertEquals(20, $actual->count());
    }

    /**
     * @test
     */
    public function test_it_should_be_ordered_by_post_count_desc()
    {
        // GIVEN
        $tag = factory(Tag::class)->create();
        $this->createPostsForTags(1, true, [$tag]);
        $mostUsedTag = factory(Tag::class)->create();
        $this->createPostsForTags(2, true, [$mostUsedTag]);
        // WHEN
        /** @var Collection $actual */
        $actual = $this->underTest->getForTagCloud();
        // THEN
        $this->assertEquals(2, $actual->get(0)->posts_count);
        $this->assertEquals(1, $actual->get(1)->posts_count);
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
