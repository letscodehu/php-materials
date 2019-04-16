<?php

namespace Tests\Unit;


use App\Persistence\Model\Post;
use App\Persistence\Repository\CachingPostRepositoryDecorator;
use App\Persistence\Repository\PostRepository;
use Illuminate\Cache\Repository;
use Illuminate\Cache\TaggedCache;
use Tests\TestCase;

class CachingPostRepositoryDecoratorTest extends TestCase {


    /**
     * @var TaggedCache
     */
    private $taggedCache;
    /**
     * @var PostRepository
     */
    private $underTest;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockCache;

    public function setUp() {
        $this->mockRepository = $this->getMockBuilder(PostRepository::class)
            ->getMock();
        $this->mockCache = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->taggedCache = $this->getMockBuilder(TaggedCache::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->underTest = new CachingPostRepositoryDecorator($this->mockRepository, $this->mockCache);
    }

    /**
     * @test
     */
    public function findAllPublic_should_return_cached_results()
    {
        // GIVEN
        $expectedResult = "cachedValue";
        $key = "page_1:20";
        $tagname = "allPublicPost";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagname)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->never())
            ->method("has")
            ->with($key);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn($expectedResult);
        // WHEN
        $actual = $this->underTest->findAllPublic(1,20);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findAllPublic_should_put_results_to_cache()
    {
        // GIVEN
        $expectedResult = "fromDB";
        $key = "page_1:20";
        $tagname = "allPublicPost";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagname)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn(null);
        $this->mockRepository->expects($this->once())
            ->method("findAllPublic")
            ->with(1,20)
            ->willReturn($expectedResult);
        $this->taggedCache
            ->expects($this->once())
            ->method("put")
            ->with($key, $expectedResult, 60);
        // WHEN
        $actual = $this->underTest->findAllPublic(1,20);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findMostViewed_should_return_cached_value()
    {
        // GIVEN
        $expectedResult = "cachedValue";
        $key = "post:20";
        $tagName = "mostViewed";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagName)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn($expectedResult);
        // WHEN
        $actual = $this->underTest->findMostViewed(20);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findMostViewed_should_store_in_cache()
    {
        // GIVEN
        $expectedResult = "fromDB";
        $key = "post:20";
        $tagname = "mostViewed";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagname)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn(null);
        $this->mockRepository->expects($this->once())
            ->method("findMostViewed")
            ->with(20)
            ->willReturn($expectedResult);
        $this->taggedCache
            ->expects($this->once())
            ->method("put")
            ->with($key, $expectedResult, 60);
        // WHEN
        $actual = $this->underTest->findMostViewed(20);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findByCategory_should_return_cached_value()
    {
        // GIVEN
        $expectedResult = "cachedValue";
        $category = "Ezekiel";
        $page = 25;
        $size = 17;
        $key = "Ezekiel:25:17";
        $tagName = "category";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagName)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn($expectedResult);
        // WHEN
        $actual = $this->underTest->findByCategory($category, $page, $size);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findByCategory_should_store_value_in_cache()
    {
        // GIVEN
        $expectedResult = "fromDB";
        $category = "Ezekiel";
        $page = 25;
        $size = 17;
        $key = "Ezekiel:25:17";
        $tagName = "category";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagName)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn(null);
        $this->mockRepository->expects($this->once())
            ->method("findByCategory")
            ->with($category, $page, $size)
            ->willReturn($expectedResult);
        $this->taggedCache
            ->expects($this->once())
            ->method("put")
            ->with($key, $expectedResult, 60);
        // WHEN
        $actual = $this->underTest->findByCategory($category, $page, $size);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findByTag_should_return_cached_results()
    {
        // GIVEN
        $expectedResult = "cachedValue";
        $tag = "Ezekiel";
        $page = 25;
        $size = 17;
        $key = "Ezekiel:25:17";
        $tagName = "tag";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagName)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn($expectedResult);
        // WHEN
        $actual = $this->underTest->findByTag($tag, $page, $size);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findByTag_should_fill_cache_with_values()
    {
        // GIVEN
        $expectedResult = "fromDB";
        $slug = "Ezekiel";
        $page = 25;
        $size = 17;
        $key = "Ezekiel:25:17";
        $tagName = "tag";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagName)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn(null);
        $this->mockRepository->expects($this->once())
            ->method("findByTag")
            ->with($slug, $page, $size)
            ->willReturn($expectedResult);
        $this->taggedCache
            ->expects($this->once())
            ->method("put")
            ->with($key, $expectedResult, 60);
        // WHEN
        $actual = $this->underTest->findByTag($slug, $page, $size);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findByAuthor_should_return_cached_value()
    {
        // GIVEN
        $expectedResult = "cachedValue";
        $author = "Ezekiel";
        $page = 25;
        $size = 17;
        $key = "Ezekiel:25:17";
        $tagName = "author";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagName)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn($expectedResult);
        // WHEN
        $actual = $this->underTest->findByAuthor($author, $page, $size);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findByAuthor_should_fill_cache_with_values()
    {
        // GIVEN
        $expectedResult = "fromDB";
        $author = "Ezekiel";
        $page = 25;
        $size = 17;
        $key = "Ezekiel:25:17";
        $tagName = "author";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagName)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn(null);
        $this->mockRepository->expects($this->once())
            ->method("findByAuthor")
            ->with($author, $page, $size)
            ->willReturn($expectedResult);
        $this->taggedCache
            ->expects($this->once())
            ->method("put")
            ->with($key, $expectedResult, 60);
        // WHEN
        $actual = $this->underTest->findByAuthor($author, $page, $size);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findBySlugAndPublishedDate_should_return_cached_value()
    {
        // GIVEN
        $expectedResult = "cachedValue";
        $date = "2017-10-17";
        $slug = "what-is-love";
        $key = "what-is-love:2017-10-17";
        $tagName = "individualPost";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagName)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn($expectedResult);
        // WHEN
        $actual = $this->underTest->findBySlugAndPublishedDate($slug, $date);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function findBySlugAndPublishedDate_should_fill_the_cache()
    {
        // GIVEN
        $expectedResult = "cachedValue";
        $date = "2017-10-17";
        $slug = "what-is-love";
        $key = "what-is-love:2017-10-17";
        $tagName = "individualPost";
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagName)
            ->willReturn($this->taggedCache);
        $this->taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn(null);
        $this->mockRepository->expects($this->once())
            ->method("findBySlugAndPublishedDate")
            ->with($slug, $date)
            ->willReturn($expectedResult);
        $this->taggedCache
            ->expects($this->once())
            ->method("put")
            ->with($key, $expectedResult, 60);
        // WHEN
        $actual = $this->underTest->findBySlugAndPublishedDate($slug, $date);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

    /**
     * @test
     */
    public function deleteById_should_empty_caches()
    {
        // GIVEN
        $id = 1;
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with(["category", "mostViewed", "tag", "author", "allPublicPost", "individualPost"])
            ->willReturn($this->taggedCache);
        $this->mockRepository->expects($this->once())
            ->method("deleteById")
            ->with($id);
        $this->taggedCache->expects($this->once())
            ->method("flush");
        // WHEN
        $this->underTest->deleteById($id);
        // THEN
    }

    /**
     * @test
     */
    public function save_should_empty_caches()
    {
        // GIVEN
        $post = $this->createMock(Post::class);
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with(["category", "mostViewed", "tag", "author", "allPublicPost", "individualPost"])
            ->willReturn($this->taggedCache);
        $this->mockRepository->expects($this->once())
            ->method("save")
            ->with($post);
        $this->taggedCache->expects($this->once())
            ->method("flush");
        // WHEN
        $this->underTest->save($post);
        // THEN
    }

}