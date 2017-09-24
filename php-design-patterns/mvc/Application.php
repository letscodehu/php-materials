<?php

class Application {

    private $dashboardController;

    public function __construct(DashboardController $dashboardController) {
        $this->dashboardController = $dashboardController;
    }

    public function run() {
        $modelAndView = $this->dashboardController->show();
        extract($modelAndView["data"]);
        require "templates". DIRECTORY_SEPARATOR . $modelAndView["view"] . ".phtml";
    }

}