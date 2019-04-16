<?php

namespace App\Http\ViewModel\Transformer;

use App\Http\ViewModel\Link;
use App\Persistence\Model\Author;
use App\Persistence\Model\Category;
use App\Persistence\Model\Post;
use PHPUnit\Framework\TestCase;

class PostPreviewTransformerTest extends TestCase
{
    private $authorName = "author name";


    /**
     * @var PostPreviewTransformer
     */
    private $underTest;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $postLinkTransformer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $excerptTransformer;


    protected function setUp()
    {
        $this->postLinkTransformer = $this->createMock(PostLinkTransformer::class);
        $this->excerptTransformer = $this->createMock(ExcerptTransformer::class);
        $this->underTest = new PostPreviewTransformer($this->excerptTransformer, $this->postLinkTransformer);
    }

    /**
     * @test
     */
    public function it_should_transform_title_as_the_same()
    {
        // GIVEN
        $post = $this->getMockPost();
        $post->method("getTitle")
            ->willReturn("title");
        // WHEN
        $actual = $this->underTest->transform($post);
        // THEN
        $this->assertEquals($actual->getTitle(), $post->getTitle());
    }

    /**
     * @test
     */
    public function it_should_transform_published_as_the_same()
    {
        // GIVEN
        $post = $this->getMockPost();
        $post->method('getDatePublished')
            ->willReturn('published');
        // WHEN
        $actual = $this->underTest->transform($post);
        // THEN
        $this->assertEquals($actual->getPublished(), $post->getDatePublished());
    }

    /**
     * @test
     */
    public function it_should_use_excerpt_transformer()
    {
        // GIVEN
        $article = "article";
        $post = $this->getMockPost();
        $post->method('getArticle')
            ->willReturn($article);
        $excerpt = "excerpt";
        $post->article = $article;
        $this->excerptTransformer->method('transform')
            ->with($article)
            ->willReturn($excerpt);
        // WHEN
        $actual = $this->underTest->transform($post);
        // THEN
        $this->assertEquals($excerpt, $actual->getExcerpt());
    }

    /**
     * @test
     */
    public function it_should_transform_authors_name_as_same()
    {
        // GIVEN
        $post = $this->getMockPost();
        // WHEN
        $actual = $this->underTest->transform($post);
        // THEN
        $this->assertEquals($this->authorName, $actual->getAuthorName());
    }

    /**
     * @test
     */
    public function it_should_transform_categories_name()
    {
        // GIVEN
        $categoryName = "category name";
        $post = $this->createMock(Post::class);
        $category = new Category();
        $category->name_clean = $categoryName;
        $categories = collect([$category]);
        $post->method('__get')
            ->withConsecutive(['author'],['category'])
            ->willReturnOnConsecutiveCalls(new Author(), $categories);
        // WHEN
        $actual = $this->underTest->transform($post);
        // THEN
        $this->assertEquals($categoryName, $actual->getCategories()[0]);
    }

    /**
     * @test
     */
    public function it_should_transform_post_link()
    {
        // GIVEN
        $post = $this->getMockPost();
        $slug = "some slug";
        $post->method('getTitleClean')
            ->willReturn($slug);
        $link = new Link(null, null);
        $this->postLinkTransformer->method('transform')
            ->with($slug)
            ->willReturn($link);
        // WHEN
        $actual = $this->underTest->transform($post);
        // THEN
        $this->assertEquals($link, $actual->getLink());
    }

    private function getMockPost() {
        $post = $this->getMockBuilder(Post::class)
            ->getMock();
        $author = new Author();
        $author->display_name = $this->authorName;
        $post->author = $author;
        $post->method('__get')
            ->withConsecutive(['author'],['category'])
            ->willReturnOnConsecutiveCalls($author, collect([]));
        return $post;
    }


}
