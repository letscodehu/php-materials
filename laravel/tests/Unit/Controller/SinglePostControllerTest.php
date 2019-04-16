<?php

namespace App\Http\Controllers;

use App\Http\ViewFacade\BlogFrontendFacade;
use Illuminate\View\Factory;
use PHPUnit\Framework\TestCase;

class SinglePostControllerTest extends TestCase
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
            ->setMethods(["assembleSinglePostModel", "assembleMainPageModel"])
            ->getMock();
        $this->underTest = new SinglePostController($this->mockViewFactory, $this->mockFacade);
    }

    /**
     * @test
     */
    public function it_should_return_viewfactories_result()
    {
        // GIVEN
        $view = "view";
        $model = "model";
        $this->mockFacade
            ->method("assembleSinglePostModel")
            ->with("2019", "04", "16", "08", "24", "00", "slug")
            ->willReturn($model);
        $this->mockViewFactory->expects($this->once())
            ->method("make")
            ->with("single", ["model" => $model])
            ->willReturn($view);
        // WHEN
        $actual = $this->underTest->handle("2019", "04", "16", "08", "24", "00", "slug");
        // THEN
        $this->assertEquals($view, $actual);
    }

    /**
     * @test
     */
    public function it_should_invoke_facade_with_route_parameters()
    {
        // GIVEN
        $this->mockFacade->expects($this->once())
            ->method("assembleSinglePostModel")
            ->with("2019", "04", "16", "08", "24", "00", "slug");
        // WHEN
        $this->underTest->handle("2019", "04", "16", "08", "24", "00", "slug");
        // THEN
    }

}
