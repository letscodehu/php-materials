<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.12.09.
 * Time: 11:07
 */

namespace Tests\Unit\ViewModel;

use App\Http\ViewModel\TagCloud;

class TagCloudTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TagCloud
     */
    private $underTest;

    public function setUp()
    {
        $this->underTest = new TagCloud(["first", "second", "third"]);
    }

    /**
     * @test
     */
    public function testCurrentReturnsFirstElementWhenStarted()
    {
        // GIVEN
        // WHEN
        $actual = $this->underTest->current();
        // THEN
        $this->assertEquals("first", $actual);
    }

    /**
     * @test
     */
    public function testKeyReturnsZeroWhenStarting()
    {
        // GIVEN
        // WHEN
        $actual = $this->underTest->key();
        // THEN
        $this->assertEquals(0, $actual);
    }


    /**
     * @test
     */
    public function testValidReturnsTrueWhenStarting()
    {
        // GIVEN
        // WHEN
        $actual = $this->underTest->valid();
        // THEN
        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function testNextMovesToSecondElement()
    {
        // GIVEN
        // WHEN
        $this->underTest->next();
        // THEN
        $this->assertEquals("second", $this->underTest->current());
        $this->assertEquals(1, $this->underTest->key());
    }

    /**
     * @test
     */
    public function testRewindMovesToFirstElement()
    {
        // GIVEN
        $this->underTest->next();
        // WHEN
        $this->underTest->rewind();
        // THEN
        $this->assertEquals("first", $this->underTest->current());
        $this->assertEquals(0, $this->underTest->key());
    }

    /**
     * @test
     */
    public function testCurrentReturnsFalseWhenEmpty()
    {
        // GIVEN
        $this->underTest = new TagCloud([]);
        // WHEN
        $actual = $this->underTest->current();
        // THEN
        $this->assertFalse($actual);
    }

    /**
     * @test
     */
    public function testKeyReturnsNullWhenEmpty()
    {
        // GIVEN
        $this->underTest = new TagCloud([]);
        // WHEN
        $actual = $this->underTest->key();
        // THEN
        $this->assertNull($actual);
    }

    /**
     * @test
     */
    public function testValidReturnsFalseWhenEmpty()
    {
        // GIVEN
        $this->underTest = new TagCloud([]);
        // WHEN
        $actual = $this->underTest->valid();
        // THEN
        $this->assertFalse($actual);
    }


}
