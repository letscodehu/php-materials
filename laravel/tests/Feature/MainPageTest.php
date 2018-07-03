<?php

namespace Tests\Feature;

use App\Http\ViewModel\MainPageModel;
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
