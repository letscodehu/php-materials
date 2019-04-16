<?php

require "Application.php";
require "DummyRenderer.php";
require "ModelAndView.php";
require "DashboardController.php";
require "UserService.php";
require "UserListFactory.php";
require "MessageSource.php";

$app = new Application(
    new DashboardController(new UserService, new UserListFactory, new MessageSource),
    new DummyRenderer
);

$app->run();