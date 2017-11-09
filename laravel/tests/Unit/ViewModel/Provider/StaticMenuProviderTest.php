<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.10.26.
 * Time: 22:53
 */

namespace Tests\Unit\ViewModel\Provider;

use App\Http\ViewModel\Factory\LinkFactory;
use App\Http\ViewModel\Link;
use App\Http\ViewModel\Menu;
use App\Http\ViewModel\Provider\StaticMenuProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Translation\Translator;

class StaticMenuProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $logger;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $translator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $linkFactory;

    /**
     * @var StaticMenuProvider
     */
    private $underTest;

    public function setUp()
    {
        $this->logger = $this->getMockBuilder(Log::class)
            ->setMethods(["error"])
            ->getMockForAbstractClass();
        $this->translator = $this->getMockBuilder(Translator::class)
            ->setMethods(["trans"])
            ->getMockForAbstractClass();
        $this->repository = $this->getMockBuilder(Repository::class)
            ->setMethods(["get"])
            ->getMockForAbstractClass();
        $this->linkFactory = $this->getMockBuilder(LinkFactory::class)
            ->getMock();
        $this->underTest = new StaticMenuProvider($this->repository, $this->translator,
                $this->logger, $this->linkFactory);
    }

    /**
     * @test
     */
    public function provideShouldReturnAMenuInstance()
    {
        // GIVEN
        $this->repository->method("get")
            ->with("view.menu");
        // WHEN
        $actual = $this->underTest->provide();
        // THEN
        $this->assertInstanceOf(Menu::class, $actual);
    }

    /**
     * @test
     */
    public function provideShouldRetrieveMenuItemsFromConfig()
    {
        // GIVEN
        $this->repository->expects($this->once())
            ->method("get")
            ->with("view.menu");
        // WHEN
        $this->underTest->provide();
        // THEN
    }


    /**
     * @test
     */
    public function provideShouldTranslateTitles()
    {
        // GIVEN
        $configArray = $this->getConfigArray();
        $this->repository->method("get")
            ->with("view.menu")
            ->willReturn($configArray);
        $this->translator->expects($this->exactly(3))
            ->method("trans")
            ->withConsecutive(
                ["key1"],
                ["key2"],
                ["key3"]
            );
        // WHEN
        $this->underTest->provide();
        // THEN
    }

    /**
     * @test
     */
    public function provideShouldHandleMissingConfigGracefully()
    {
        // GIVEN
        $this->logger->expects($this->once())
            ->method("error")
            ->with("Menu provider failed to retrieve menu from config.");
        // WHEN
        $this->underTest->provide();
        // THEN
    }

    /**
     * @test
     */
    public function provideShouldCreateLinksWithFactory()
    {
        // GIVEN
        $configArray = $this->getConfigArray();
        $this->repository->method("get")
            ->with("view.menu")
            ->willReturn($configArray);
        $this->translator
            ->method("trans")
            ->willReturnOnConsecutiveCalls("trans1", "trans2", "trans3");
        $this->linkFactory->expects($this->exactly(3))
            ->method("create")
            ->withConsecutive(
                ["url1", "trans1"],
                ["url2", "trans2"],
                ["url3", "trans3"]
            );
        // WHEN
        $this->underTest->provide();
        // THEN
    }

    /**
     * @test
     */
    public function provideShouldReturnMenuContainingLinks()
    {
        // GIVEN
        $configArray = $this->getConfigArray();
        $this->repository->method("get")
            ->with("view.menu")
            ->willReturn($configArray);
        $this->translator
            ->method("trans")
            ->willReturnOnConsecutiveCalls("trans1", "trans2", "trans3");
        $mockLink1 = $this->getMockBuilder(Link::class);
        $mockLink2 = $this->getMockBuilder(Link::class);
        $mockLink3 = $this->getMockBuilder(Link::class);
        $this->linkFactory->method("create")
            ->willReturnOnConsecutiveCalls(
                $mockLink1, $mockLink2, $mockLink3
            );
        // WHEN
        $actual = $this->underTest->provide();
        // THEN
        $linkArray = $actual->getContent();
        $this->assertEquals($mockLink1, $linkArray[0]);
        $this->assertEquals($mockLink2, $linkArray[1]);
        $this->assertEquals($mockLink3, $linkArray[2]);
    }

    private function getConfigArray()
    {
        return [
            [
                "url" => "url1",
                "title" => "key1"
            ],
            [
                "url" => "url2",
                "title" => "key2"
            ],
            [
                "url" => "url3",
                "title" => "key3"
            ]
        ];
    }

}
