<?php

class DashboardController
{
    private $userService;
    private $userListFactory;
    private $messageSource;

    public function __construct(
        UserService $userService,
        UserListFactory $userListFactory,
        MessageSource $messageSource
    ) {
        $this->userService = $userService;
        $this->userListFactory = $userListFactory;
        $this->messageSource = $messageSource;
    }

    public function show()
    {
        return new ModelAndView(
            "dashboard",
            [
                "title" => $this->messageSource->get("dashboard.users.title"),
                "users" => $this->userListFactory->create(
                    $this->userService->getUsers()
                )
            ]
        );
    }
}
