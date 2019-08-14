<?php


namespace App\Http\ViewModel;


class SinglePostModel
{

    /**
     * @var Menu
     */
    private $menu;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $analyticsKey;

    /**
     * SinglePostModel constructor.
     * @param Menu $menu
     * @param string $content
     * @param string $title
     * @param string $analyticsKey
     */
    public function __construct(Menu $menu, string $content, string $title, string $analyticsKey)
    {
        $this->menu = $menu;
        $this->content = $content;
        $this->title = $title;
        $this->analyticsKey = $analyticsKey;
    }

    /**
     * @return Menu
     */
    public function getMenu(): Menu
    {
        return $this->menu;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getAnalyticsKey(): string
    {
        return $this->analyticsKey;
    }



}