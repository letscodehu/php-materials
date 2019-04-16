<?php

namespace Tests\Unit\Controller;


use App\Http\Controllers\MainPageController;
use App\Http\ViewFacade\BlogFrontendFacade;
use Illuminate\View\Factory;
use Symfony\Component\HttpFoundation\Request;

class MainPageControllerTest extends \PHPUnit_Framework_TestCase
{

    private $underTest;
    private $mockViewFactory;
    private $mockFacade;

    public function setUp()
    {
        $this->mockViewFactory = $this->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockFacade = $this->getMockBuilder(BlogFrontendFacade::class)
            ->setMethods(["assembleMainPageModel"])
            ->getMock();
        $this->underTest = new MainPageController($this->mockViewFactory, $this->mockFacade);
    }

    /**
     * @test
     */
    public function index_should_populate_model_based_on_services()
    {
        // GIVEN
        $view = "Warm welcome";
        $viewName = "index";
        $someData = "someData";
        $request = $this->getMockBuilder(Request::class)->getMock();
        $this->mockViewFactory->expects($this->once())
            ->method("make")
            ->with($viewName, ["model" => $someData])
            ->willReturn($view);
        $this->mockFacade->expects($this->once())
            ->method("assembleMainPageModel")
            ->with($request)
            ->willReturn($someData);
        // WHEN
        $actual = $this->underTest->index($request);
        // THEN
        $this->assertEquals($view, $actual);
    }

}