<?php

namespace Tests\Unit\ViewModel\Transformer;


use App\Http\ViewModel\Transformer\PostLinkTransformer;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Translation\Translator;
use PHPUnit\Framework\TestCase;

class PostLinkTransformerTest extends TestCase
{

    /**
     * @var PostLinkTransformer
     */
    private $underTest;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $configRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $translator;

    protected function setUp()
    {
        $this->configRepository = $this->getMockBuilder(Repository::class)
            ->setMethods(["get"])
            ->getMockForAbstractClass();
        $this->translator = $this->getMockBuilder(Translator::class)
            ->setMethods(['trans'])
            ->getMockForAbstractClass();
        $this->underTest = new PostLinkTransformer($this->translator, $this->configRepository);
    }

    /**
     * @test
     */
    public function it_should_create_links_with_localized_more_text()
    {
        // GIVEN
        $translated = "Read more";
        $key = "main_page.read_more";
        $this->translator->method('trans')
            ->with($key)
            ->willReturn($translated);
        $date = "2002-04-25 03:06:10";
        // WHEN
        $actual = $this->underTest->transform("slug", $date);
        // THEN
        $this->assertEquals($translated, $actual->getTitle());
    }

    /**
     * @test
     */
    public function it_should_create_links_based_on_config_and_date_and_slug()
    {
        // GIVEN
        $key = "view.main_page.post_base_url";
        $baseUrl = "baseurl";
        $this->configRepository->method("get")
            ->with($key)
            ->willReturn($baseUrl);
        $slug = "slug";
        $date = "2002-04-25 03:06:10";
        $expected = "$baseUrl/2002/04/25/03/06/10/$slug";
        // WHEN
        $actual = $this->underTest->transform($slug, $date);
        // THEN
        $this->assertEquals($expected, $actual->getUrl());
    }


}