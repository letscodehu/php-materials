<?php

namespace Tests\Unit;


use App\Persistence\Repository\CachingPostRepositoryDecorator;
use App\Persistence\Repository\PostRepository;
use Illuminate\Cache\Repository;
use Illuminate\Cache\TaggedCache;
use Tests\TestCase;

class CachingPostRepositoryDecoratorTest extends TestCase {

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

        $taggedCache = $this->getMockBuilder(TaggedCache::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagname)
            ->willReturn($taggedCache);
        $taggedCache
            ->expects($this->never())
            ->method("has")
            ->with($key);
        $taggedCache
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
        $taggedCache = $this->getMockBuilder(TaggedCache::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockCache->expects($this->once())
            ->method("tags")
            ->with($tagname)
            ->willReturn($taggedCache);
        $taggedCache
            ->expects($this->once())
            ->method("get")
            ->with($key)
            ->willReturn(null);
        $this->mockRepository->expects($this->once())
            ->method("findAllPublic")
            ->with(1,20)
            ->willReturn($expectedResult);
        $taggedCache
            ->expects($this->once())
            ->method("put")
            ->with($key, $expectedResult);
        // WHEN
        $actual = $this->underTest->findAllPublic(1,20);
        // THEN
        $this->assertEquals($expectedResult, $actual);
    }

}