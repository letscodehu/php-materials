<?php

namespace Tests\Unit\ViewModel\Factory;

use App\Http\ViewModel\Factory\TagCloudLinkFactory;

class TagCloudLinkFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TagCloudLinkFactory
     */
    private $underTest;

    public function setUp()
    {
        $this->underTest = new TagCloudLinkFactory();
    }

    /**
     * @test
     */
    public function createShouldReturnLinkWithCorrectUrl()
    {
        // GIVEN
        $url = "url";
        $title = "title";
        $fontSize = "fontSize";
        // WHEN
        $actual = $this->underTest->create($url, $title, $fontSize);
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
        $fontSize = "fontSize";
        // WHEN
        $actual = $this->underTest->create($url, $title, $fontSize);
        // THEN
        $this->assertEquals($title, $actual->getTitle());
    }


    /**
     * @test
     */
    public function createShouldReturnLinkWithCorrectFontSize()
    {
        // GIVEN
        $url = "url";
        $title = "title";
        $fontSize = "fontSize";
        // WHEN
        $actual = $this->underTest->create($url, $title, $fontSize);
        // THEN
        $this->assertEquals($fontSize, $actual->getFontSize());
    }


}
