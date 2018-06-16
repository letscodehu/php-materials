<?php

namespace App\Http\ViewModel\Provider;

use App\Http\ViewModel\Factory\LinkFactory;
use App\Http\ViewModel\Link;
use App\Http\ViewModel\PostPreview;
use App\Http\ViewModel\Transformer\PostPreviewTransformer;
use App\Persistence\Model\Post;
use App\Persistence\Repository\PostRepository;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Pagination\Paginator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $postPreviewTransformer;


    protected function setUp()
    {
        $this->postRepository = $this->getMockBuilder(PostRepository::class)
            ->setMethods(["findMostViewed"])
            ->getMockForAbstractClass();
        $this->configRepository = $this->getMockBuilder(Repository::class)
            ->setMethods(["get"])
            ->getMockForAbstractClass();
        $this->postPreviewTransformer = $this->getMockBuilder(PostPreviewTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->linkFactory = $this->getMockBuilder(LinkFactory::class)
            ->getMock();
        $this->underTest = new EloquentPostProvider($this->postRepository, $this->configRepository, $this->linkFactory,
                $this->postPreviewTransformer);
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

    /**
     * @test
     */
    public function retrievePostsForMainPage_invoke_postrepository_with_params_from_request()
    {
        // GIVEN
        $request = $this->createMock(Request::class);
        $pageNumber = 1;
        $pageSize = 25;
        $request->method("get")
            ->withConsecutive(['page'], ['size'])
            ->willReturnOnConsecutiveCalls($pageNumber, $pageSize);

        $this->postRepository->expects($this->once())->method("findAllPublic")
            ->with($pageNumber, $pageSize)
            ->willReturn(new Paginator([], 1));
        // WHEN
        $this->underTest->retrievePostsForMainPage($request);
        // THEN
    }

    /**
     * @test
     */
    public function retrievePostsForMainPage_should_map_returned_elements_with_transformer()
    {
        // GIVEN
        $request = $this->createMock(Request::class);
        $pageNumber = 1;
        $pageSize = 25;
        $request->method("get")
            ->withConsecutive(['page'], ['size'])
            ->willReturnOnConsecutiveCalls($pageNumber, $pageSize);
        $firstPost = new Post();
        $secondPost = new Post();
        $paginator = new Paginator([$firstPost, $secondPost], 25);
        $firstPostPreview = PostPreview::builder()->build();
        $secondPostPreview = PostPreview::builder()->build();
        $this->postRepository->method('findAllPublic')
            ->willReturn($paginator);
        $this->postPreviewTransformer->method('transform')
            ->withConsecutive([$firstPost], [$secondPost])
            ->willReturnOnConsecutiveCalls($firstPostPreview, $secondPostPreview);
        // WHEN
        $actual = $this->underTest->retrievePostsForMainPage($request);
        // THEN
        $this->assertEquals($actual->items()[0], $firstPostPreview);
        $this->assertEquals($actual->items()[1], $secondPostPreview);
    }

}
