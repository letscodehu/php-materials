<?php

namespace App\Http\ViewModel\Provider;

use App\Http\ViewModel\Factory\LinkFactory;
use App\Http\ViewModel\Link;
use App\Persistence\Model\Post;
use App\Persistence\Repository\PostRepository;
use Illuminate\Contracts\Config\Repository;
use PHPUnit\Framework\TestCase;

class EloquentPostProviderTest extends TestCase
{

    /**
     * @var PostProvider
     */
    private $underTest;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $postRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $configRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $linkFactory;


    protected function setUp()
    {
        $this->postRepository = $this->getMockBuilder(PostRepository::class)
            ->setMethods(["findMostViewed"])
            ->getMockForAbstractClass();
        $this->configRepository = $this->getMockBuilder(Repository::class)
            ->setMethods(["get"])
            ->getMockForAbstractClass();
        $this->linkFactory = $this->getMockBuilder(LinkFactory::class)
            ->getMock();
        $this->underTest = new EloquentPostProvider($this->postRepository, $this->configRepository, $this->linkFactory);
    }

    /**
     * @test
     */
    public function retrieveTrendingPosts_should_invoke_repository_with_limit_20()
    {
        // GIVEN
        $limit = 20;
        $this->postRepository->expects($this->once())
            ->method("findMostViewed")
            ->with($limit)
            ->willReturn([]);
        // WHEN
        $this->underTest->retrieveTrendingPosts();
        // THEN
    }

    /**
     * @test
     */
    public function retrieveTrendingPosts_should_retrieve_baseurl_from_config()
    {
        // GIVEN
        $this->configRepository->expects($this->once())
            ->method("get")
            ->with("view.main_page.post_base_url");
        $this->postRepository
            ->method("findMostViewed")
            ->willReturn([]);
        // WHEN
        $this->underTest->retrieveTrendingPosts();
        // THEN
    }

    /**
     * @test
     */
    public function retrieveTrendingPosts_should_map_results_with_linkfactory()
    {
        // GIVEN
        $post = $this->createMock(Post::class);
        $post->expects($this->once())
            ->method("getTitle")
            ->willReturn("title");
        $post->expects($this->once())
            ->method("getTitleClean")
            ->willReturn("title_clean");
        $this->configRepository->method("get")
            ->willReturn("baseurl/");
        $this->postRepository->method("findMostViewed")
            ->willReturn([$post]);
        $mappedValue = $this->createMock(Link::class);
        $this->linkFactory->expects($this->once())
            ->method("create")
            ->with("baseurl/title_clean", "title")
            ->willReturn($mappedValue);
        // WHEN
        $actual = $this->underTest->retrieveTrendingPosts();
        // THEN
        $this->assertEquals($mappedValue, $actual[0]);
    }

}
