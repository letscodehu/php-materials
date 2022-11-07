<?php

class Application
{
    private $dashboardController;
    private $dummyRenderer;

    public function __construct(DashboardController $dashboardController, DummyRenderer $dummyRenderer)
    {
        $this->dashboardController = $dashboardController;
        $this->dummyRenderer = $dummyRenderer;
    }

    public function run()
    {
        // route
        $modelAndView = $this->dashboardController->show();
        $this->dummyRenderer->render($modelAndView);
    }
}
