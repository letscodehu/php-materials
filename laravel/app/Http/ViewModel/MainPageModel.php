<?php

namespace App\Http\ViewModel;


use Illuminate\Contracts\Pagination\Paginator;

class MainPageModel
{

    /**
     * @var Paginator
     */
    private $content;

    /**
     * @var Menu
     */
    private $menu;

    /**
     * @var string
     */
    private $analyticsKey;

    /**
     * @var Link[]
     */
    private $trending;

    /**
     * @var TagCloud
     */
    private $tagCloud;

    /**
     * @var string
     */
    private $facebookUrl;

    /**
     * @var string
     */
    private $twitterUrl;

    /**
     * @var string
     */
    private $feedUrl;

    /**
     * MainPageModel constructor.
     * @param MainPageModelBuilder $builder
     */
    public function __construct(MainPageModelBuilder $builder)
    {
        $this->content = $builder->getContent();
        $this->menu = $builder->getMenu();
        $this->analyticsKey = $builder->getAnalyticsKey();
        $this->trending = $builder->getTrending();
        $this->tagCloud = $builder->getTagCloud();
        $this->facebookUrl = $builder->getFacebookUrl();
        $this->twitterUrl = $builder->getTwitterUrl();
        $this->feedUrl = $builder->getFeedUrl();
    }


    /**
     * @return Paginator
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @return string
     */
    public function getAnalyticsKey()
    {
        return $this->analyticsKey;
    }

    /**
     * @return Link[]
     */
    public function getTrending()
    {
        return $this->trending;
    }

    /**
     * @return TagCloud
     */
    public function getTagCloud()
    {
        return $this->tagCloud;
    }

    /**
     * @return string
     */
    public function getFacebookUrl()
    {
        return $this->facebookUrl;
    }

    /**
     * @return string
     */
    public function getTwitterUrl()
    {
        return $this->twitterUrl;
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return $this->feedUrl;
    }

    /**
     * Return new MainPageModelBuilder instance.
     */
    public static function builder() {
        return new MainPageModelBuilder();
    }


}