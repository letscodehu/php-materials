<?php


namespace App\Http\ViewModel;


class SinglePostModel
{

    /**
     * @var Menu
     */
    private $menu;

    /**
     * SinglePostModel constructor.
     * @param Menu $menu
     */
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

}