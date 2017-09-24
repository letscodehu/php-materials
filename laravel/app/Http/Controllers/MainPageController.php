<?php

namespace App\Http\Controllers;

use App\Http\ViewFacade\BlogFrontendFacade;
use Illuminate\View\Factory;
use Symfony\Component\HttpFoundation\Request;

class MainPageController extends Controller
{

    /**
     * @var Factory
     */
    private $factory;
    /**
     * @var BlogFrontendFacade
     */
    private $frontendFacade;

    public function __construct(Factory $factory, BlogFrontendFacade $frontendFacade)
    {
        $this->factory = $factory;
        $this->frontendFacade = $frontendFacade;
    }

    public function index(Request $request) {
        return $this->factory->make("index", [
            "model" => $this->frontendFacade->assembleMainPageModel($request)
        ]);
    }
}
