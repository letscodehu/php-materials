<?php

class UserController
{
    private $userService;
    private $strategies;

    public function __construct(UserService $userService, array $strategies)
    {
        $this->userService = $userService;
        $this->strategies = $strategies;
    }

    public function getOne($id, $format)
    {
        $user = $this->userService->getOne($id);
        return $this->strategies[$format]->render($user);
    }
}

interface UserRenderingStrategy
{
    public function render(User $user);
}

class JsonUserRenderingStrategy implements UserRenderingStrategy
{
    public function render(User $user)
    {
        return json_encode($user);
    }
}

class XmlUserRenderingStrategy implements UserRenderingStrategy
{
    public function render(User $user)
    {
        return "<user><id>".$user->id."</id></user>";
    }
}

class HtmlUserRenderingStrategy implements UserRenderingStrategy
{
    public function render(User $user)
    {
        return "<html><body>Az ID: ".$user->id."</body></html>";
    }
}

class User
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}

class UserService
{
    public function getOne($id)
    {
        return new User($id);
    }
}
$strategies = [
    "json" => new JsonUserRenderingStrategy,
    "xml" => new XmlUserRenderingStrategy,
    "html" => new HtmlUserRenderingStrategy
];
$controller = new UserController(new UserService, $strategies);
echo $controller->getOne(5, "json").PHP_EOL;
echo $controller->getOne(5, "xml").PHP_EOL;
echo $controller->getOne(5, "html").PHP_EOL;
