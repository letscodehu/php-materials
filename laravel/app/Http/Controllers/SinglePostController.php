<?php


namespace App\Http\Controllers;


use App\Http\ViewFacade\BlogFrontendFacade;
use Illuminate\View\Factory;

class SinglePostController
{

    private $viewFactory;
    private $facade;

    public function __construct(Factory $viewFactory, BlogFrontendFacade $facade)
    {
        $this->viewFactory = $viewFactory;
        $this->facade = $facade;
    }

    public function handle($year, $month, $day, $hour, $minute, $second, $postSlug) {
        return $this->viewFactory->make("single",
            ["model" => $this->facade->assembleSinglePostModel($year, $month, $day, $hour, $minute, $second, $postSlug)]);
    }

}