<?php

namespace Tests\Unit\ViewModel\Transformer;

use App\Http\ViewModel\Transformer\ExcerptTransformer;
use PHPUnit\Framework\TestCase;

class ExcerptTransformerTest extends TestCase
{
    /**
     * @var ExcerptTransformer
     */
    private $underTest;

    protected function setUp()
    {
        $this->underTest = new ExcerptTransformer();
    }

    /**
     * @test
     */
    public function it_should_handle_more_tags()
    {
        // GIVEN
        $article = "excerpt<!-- MORE -->left of the article";
        // WHEN
        $actual = $this->underTest->transform($article);
        // THEN
        $this->assertEquals("excerpt", $actual);
    }

    /**
     * @test
     */
    public function it_should_show_whole_article_if_no_more_tags_present()
    {
        // GIVEN
        $article = "article";
        // WHEN
        $actual = $this->underTest->transform($article);
        // THEN
        $this->assertEquals($article, $actual);
    }

}