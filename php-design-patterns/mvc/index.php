<?php

require "Application.php";
require "DashboardController.php";
require "UserService.php";

(new Application(new DashboardController(new UserService())))->run();
