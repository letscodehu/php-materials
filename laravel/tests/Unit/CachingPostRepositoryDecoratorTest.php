<?php

namespace Tests\Unit;


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

}