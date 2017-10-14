<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.10.01.
 * Time: 17:24
 */

namespace Tests\Unit\ViewFacade;


use App\Http\ViewFacade\DefaultBlogFrontendFacade;
use App\Http\ViewModel\MainPageModel;
use App\Http\ViewModel\Menu;
use App\Http\ViewModel\Provider\MenuProvider;
use App\Http\ViewModel\Provider\PostProvider;
use App\Http\ViewModel\Provider\TagProvider;
use App\Http\ViewModel\TagCloud;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class DefaultBlogFrontendFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var DefaultBlogFrontendFacade
     */
    private $underTest;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $menuProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $postProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $tagProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $configRepository;


    public function setUp()
    {
        $this->request = $this->getMockBuilder(Request::class)
            ->getMock();
        $this->postProvider = $this->getMockBuilder(PostProvider::class)
            ->setMethods(["retrievePostsForMainPage", "retrieveTrendingPosts"])
            ->getMock();
        $this->menuProvider = $this->getMockBuilder(MenuProvider::class)
            ->setMethods(["provide"])
            ->getMock();
        $this->tagProvider = $this->getMockBuilder(TagProvider::class)
            ->setMethods(["retrieveTagCloud"])
            ->getMock();
        $this->configRepository = $this->getMockBuilder(Repository::class)
            ->setMethods(["get"])
            ->getMockForAbstractClass();
        $this->underTest = new DefaultBlogFrontendFacade($this->menuProvider,
            $this->postProvider, $this->tagProvider, $this->configRepository);
    }

    /**
     * @test
     */
    public function assembleMainPageModel_should_return_model()
    {
        // GIVEN
        // WHEN
        $actual = $this->underTest->assembleMainPageModel($this->request);
        // THEN
        $this->assertInstanceOf(MainPageModel::class, $actual);
    }

    /**
     * @test
     */
    public function assembleMainPageModel_should_return_menu_field()
    {
        // GIVEN
        $menu = $this->getMockBuilder(Menu::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->menuProvider->expects($this->once())
            ->method("provide")
            ->willReturn($menu);
        // WHEN
        $actual = $this->underTest->assembleMainPageModel($this->request);
        // THEN
        $this->assertEquals($menu, $actual->getMenu());
    }

    /**
     * @test
     */
    public function assembleMainPageModel_should_return_content_field()
    {
        // GIVEN
        $paginator = $this->getMockBuilder(Paginator::class)->getMock();
        $this->postProvider->expects($this->once())
            ->method("retrievePostsForMainPage")
            ->with($this->request)
            ->willReturn($paginator);
        // WHEN
        $actual = $this->underTest->assembleMainPageModel($this->request);
        // THEN
        $this->assertEquals($paginator, $actual->getContent());
    }

    /**
     * @test
     */
    public function assembleMainPageModel_should_return_trending_field()
    {
        // GIVEN
        $mockArray = ["trending"];
        $this->postProvider->expects($this->once())
            ->method("retrieveTrendingPosts")
            ->willReturn($mockArray);
        // WHEN
        $actual = $this->underTest->assembleMainPageModel($this->request);
        // THEN
        $this->assertEquals($mockArray, $actual->getTrending());
    }

    /**
     * @test
     */
    public function assembleMainPageModel_should_return_tagCloud_field()
    {
        // GIVEN
        $tagCloud = $this->getMockBuilder(TagCloud::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->tagProvider->expects($this->once())
            ->method("retrieveTagCloud")
            ->willReturn($tagCloud);
        // WHEN
        $actual = $this->underTest->assembleMainPageModel($this->request);
        // THEN
        $this->assertEquals($tagCloud, $actual->getTagCloud());
    }

    /**
     * @test
     */
    public function assembleMainPageModel_should_return_facebookUrl_field()
    {
        // GIVEN
        $facebookUrl = "facebookUrl";
        $this->configRepository->expects($this->at(0))
            ->method("get")
            ->with("view.main_page.facebook_url")
            ->willReturn($facebookUrl);
        // WHEN
        $actual = $this->underTest->assembleMainPageModel($this->request);
        // THEN
        $this->assertEquals($facebookUrl, $actual->getFacebookUrl());
    }

    /**
     * @test
     */
    public function assembleMainPageModel_should_return_twitterUrl_field()
    {
        // GIVEN
        $twitterUrl = "twitterUrl";
        $this->configRepository->expects($this->at(1))
            ->method("get")
            ->with("view.main_page.twitter_url")
            ->willReturn($twitterUrl);
        // WHEN
        $actual = $this->underTest->assembleMainPageModel($this->request);
        // THEN
        $this->assertEquals($twitterUrl, $actual->getTwitterUrl());
    }

    /**
     * @test
     */
    public function assembleMainPageModel_should_return_feedUrl_field()
    {
        // GIVEN
        $feedUrl = "feedUrl";
        $this->configRepository->expects($this->at(2))
            ->method("get")
            ->with("view.main_page.feed_url")
            ->willReturn($feedUrl);
        // WHEN
        $actual = $this->underTest->assembleMainPageModel($this->request);
        // THEN
        $this->assertEquals($feedUrl, $actual->getFeedUrl());
    }


    /**
     * @test
     */
    public function assembleMainPageModel_should_return_analyticsKey_field()
    {
        // GIVEN
        $analyticsKey = "analyticsKey";
        $this->configRepository->expects($this->at(3))
            ->method("get")
            ->with("app.analytics_key")
            ->willReturn($analyticsKey);
        // WHEN
        $actual = $this->underTest->assembleMainPageModel($this->request);
        // THEN
        $this->assertEquals($analyticsKey, $actual->getAnalyticsKey());
    }

}