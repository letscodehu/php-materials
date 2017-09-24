<?php

class DashboardController {

    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function show() {
        return [
            "data" => [
                "title" => "Dashboard - Users",
                "users" => $this->userService->getUsers()
            ],
            "view" => "dashboard"
        ];
    }

}