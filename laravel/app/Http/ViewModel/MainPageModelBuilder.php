<?php

namespace App\Http\ViewModel;

use Throwable;

/**
 * Builder class for MainPageModel
 */
class MainPageModelBuilder
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
     * @return Paginator
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param Paginator $content
     * @return MainPageModelBuilder
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param Menu $menu
     * @return MainPageModelBuilder
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnalyticsKey()
    {
        return $this->analyticsKey;
    }

    /**
     * @param string $analyticsKey
     * @return MainPageModelBuilder
     */
    public function setAnalyticsKey($analyticsKey)
    {
        $this->analyticsKey = $analyticsKey;
        return $this;
    }

    /**
     * @return Link[]
     */
    public function getTrending()
    {
        return $this->trending;
    }

    /**
     * @param Link[] $trending
     * @return MainPageModelBuilder
     */
    public function setTrending($trending)
    {
        $this->trending = $trending;
        return $this;
    }

    /**
     * @return TagCloud
     */
    public function getTagCloud()
    {
        return $this->tagCloud;
    }

    /**
     * @param TagCloud $tagCloud
     * @return MainPageModelBuilder
     */
    public function setTagCloud($tagCloud)
    {
        $this->tagCloud = $tagCloud;
        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookUrl()
    {
        return $this->facebookUrl;
    }

    /**
     * @param string $facebookUrl
     * @return MainPageModelBuilder
     */
    public function setFacebookUrl($facebookUrl)
    {
        $this->facebookUrl = $facebookUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getTwitterUrl()
    {
        return $this->twitterUrl;
    }

    /**
     * @param string $twitterUrl
     * @return MainPageModelBuilder
     */
    public function setTwitterUrl($twitterUrl)
    {
        $this->twitterUrl = $twitterUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return $this->feedUrl;
    }

    /**
     * @param string $feedUrl
     * @return MainPageModelBuilder
     */
    public function setFeedUrl($feedUrl)
    {
        $this->feedUrl = $feedUrl;
        return $this;
    }

    /**
     * Creates the actual MainPageModel
     */
    public function build() {
        return new MainPageModel($this);
    }


}

class Something extends \Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}