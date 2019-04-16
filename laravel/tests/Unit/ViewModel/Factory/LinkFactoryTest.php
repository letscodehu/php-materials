<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.11.28.
 * Time: 20:45
 */

namespace Tests\Unit\ViewModel\Factory;

use App\Http\ViewModel\Factory\LinkFactory;

class LinkFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var LinkFactory
     */
    private $underTest;

    public function setUp()
    {
        $this->underTest = new LinkFactory();
    }

    /**
     * @test
     */
    public function createShouldReturnLinkWithCorrectUrl()
    {
        // GIVEN
        $url = "url";
        $title = "title";
        // WHEN
        $actual = $this->underTest->create($url, $title);
        // THEN
        $this->assertEquals($url, $actual->getUrl());
    }


    /**
     * @test
     */
    public function createShouldReturnLinkWithCorrectTitle()
    {
        // GIVEN
        $url = "url";
        $title = "title";
        // WHEN
        $actual = $this->underTest->create($url, $title);
        // THEN
        $this->assertEquals($title, $actual->getTitle());
    }

}
