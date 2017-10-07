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
        $this->underTest = new DefaultBlogFrontendFacade($this->menuProvider,
            $this->postProvider);
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

}