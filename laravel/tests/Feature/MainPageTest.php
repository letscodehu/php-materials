<?php

namespace Tests\Feature;

use App\Http\ViewModel\MainPageModel;
use App\Http\ViewModel\PostPreview;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MainPageTest extends TestCase
{

    use DatabaseMigrations, DatabaseTransactions;

    protected function setUp()
    {
        parent::setUp();
        $this->seed(\FeatureTestSeeder::class);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMainPageDisplaysCorrectly()
    {
        // GIVEN
        // WHEN
        $response = $this->get('/');
        // THEN
        $response->assertViewHas("model");
        $response->assertStatus(200);
        $viewData = $response->baseResponse->getOriginalContent()->getData();
        /** @var MainPageModel $model */
        $model = $viewData["model"];
        $this->assertStaticMetadata($model);
        $this->assertMenuLinks($model);
        $this->assertDynamicData($model);
    }

    /**
     * @test
     */
    public function testMainPagePaginationOffsetAndLimitsCorrectly()
    {
        // GIVEN
        // WHEN
        $response = $this->get('/?page=2&size=10');
        // THEN
        $viewData = $response->baseResponse->getOriginalContent()->getData();
        /** @var MainPageModel $model */
        $model = $viewData["model"];
        $this->assertTrue($model->getContent()->hasMorePages());
        $this->assertEquals(10, count($model->getContent()->items()));
        $this->assertEquals(10, $model->getContent()->perPage());
        $firstPost = $model->getContent()->items()[0];
        $lastPost = $model->getContent()->items()[9];
        $this->assertPostPreview($firstPost, "Title 10", "title-10");
        $this->assertPostPreview($lastPost, "Title 19", "title-19");
    }

    /**
     * @param $model
     */
    private function assertStaticMetadata(MainPageModel $model)
    {
        $this->assertEquals("UA-1111111-1", $model->getAnalyticsKey());
        $this->assertEquals("/feed", $model->getFeedUrl());
        $this->assertEquals("https://www.facebook.com/letscodehu/", $model->getFacebookUrl());
        $this->assertEquals("https://twitter.com/letscodehu", $model->getTwitterUrl());
    }

    private function assertDynamicData(MainPageModel $model)
    {
        $this->assertTrue($model->getContent()->hasMorePages());
        $this->assertEquals(15, count($model->getContent()->items()));
        $this->assertEquals(15, $model->getContent()->perPage());
        $firstPost = $model->getContent()->items()[0];
        $lastPost = $model->getContent()->items()[14];
        $this->assertPostPreview($firstPost, "Title 0", "title-0");
        $this->assertPostPreview($lastPost, "Title 14", "title-14");
    }

    private function assertPostPreview(PostPreview $postPreview, $expectedTitle, $expectedUrl) {
        $this->assertEquals("main_page.read_more", $postPreview->getLink()->getTitle());
        $this->assertEquals("John Doe", $postPreview->getAuthorName());
        $this->assertEquals(["php"], $postPreview->getCategories());
        $this->assertEquals("excerpt", $postPreview->getExcerpt());
        $this->assertEquals("2018-08-17 16:20:00", $postPreview->getPublished());
        $this->assertEquals($expectedTitle, $postPreview->getTitle());
        $this->assertEquals($expectedUrl, $postPreview->getLink()->getUrl());
    }

    private function assertMenuLinks(MainPageModel $model)
    {
        $menuLinks = $model->getMenu()->getContent();
        $this->assertEquals("About us", $menuLinks[0]->getTitle());
        $this->assertEquals("/about", $menuLinks[0]->getUrl());
        $this->assertEquals("Slack channel", $menuLinks[1]->getTitle());
        $this->assertEquals("https://www.letscode.hu/slack", $menuLinks[1]->getUrl());
    }
}
