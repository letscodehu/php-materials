<?php

namespace App\Http\ViewModel\Provider;

use App\Http\ViewModel\Factory\TagCloudLinkFactory;
use App\Http\ViewModel\TagCloud;
use App\Http\ViewModel\TagCloudLink;
use App\Persistence\Model\Tag;
use App\Persistence\Repository\TagRepository;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Tests\TestCase;

class EloquentTagProviderTest extends TestCase
{

    /**
     * @var EloquentTagProvider
     */
    private $underTest;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $configRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $tagCloundLinkFactory;

    protected function setUp()
    {
        $this->repository = $this->getMockBuilder(TagRepository::class)
        ->setMethods(["retrieveTagCloud"])
        ->getMockForAbstractClass();
        $this->configRepository = $this->getMockBuilder(Repository::class)
            ->setMethods(["get"])
            ->getMockForAbstractClass();
        $this->tagCloundLinkFactory = $this->getMockBuilder(TagCloudLinkFactory::class)
            ->getMock();
        $this->underTest = new EloquentTagProvider($this->repository,
            $this->configRepository, $this->tagCloundLinkFactory);
    }

    /**
     * @test
     */
    public function it_should_return_tagcloud()
    {
        // GIVEN
        $this->repository
            ->method("getForTagCloud")
            ->willReturn(new Collection([]));
        // WHEN
        $actual = $this->underTest->retrieveTagCloud();
        // THEN
        $this->assertInstanceOf(TagCloud::class, $actual);
    }

    /**
     * @test
     */
    public function it_should_invoke_repository()
    {
        // GIVEN
        $this->repository->expects($this->once())
            ->method("getForTagCloud")
            ->willReturn(new Collection());
        // WHEN
        $this->underTest->retrieveTagCloud();
        // THEN
    }

    /**
     * @test
     */
    public function it_should_get_base_url_from_config()
    {
        // GIVEN
        $this->configRepository->expects($this->once())
            ->method("get")
            ->with("view.main_page.tag_base_url");
        $this->repository
            ->method("getForTagCloud")
            ->willReturn(new Collection([]));
        // WHEN
        $this->underTest->retrieveTagCloud();
        // THEN
    }

    /**
     * @test
     */
    public function it_should_map_collection_contents()
    {
        // GIVEN
        $tag = $this->createTagMock();
        $tagCloudLink = $this->getMockBuilder(TagCloudLink::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->repository
            ->method("getForTagCloud")
        ->willReturn(new Collection([$tag]));
        $this->configRepository
            ->method("get")
            ->with("view.main_page.tag_base_url")
            ->willReturn("baseurl/");
        $this->tagCloundLinkFactory->expects($this->once())
            ->method("create")
            ->with("baseurl/slug", "title", 1)
        ->willReturn($tagCloudLink);
        // WHEN
        $actual = $this->underTest->retrieveTagCloud();
        // THEN
        $this->assertEquals($tagCloudLink, $actual->current());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createTagMock()
    {
        $tag = $this->createMock(Tag::class);
        $tag->expects($this->once())
            ->method("getTitle")
            ->willReturn("title");
        $tag->expects($this->once())
            ->method("getSlug")
            ->willReturn("slug");
        $tag->expects($this->once())
            ->method("getPostsCount")
            ->willReturn(1);
        return $tag;
    }

}
