<?php

class User
{
    private $type;
    private $name;
    private $email;
    private $subscriptions;

    /**
     * User constructor.
     * @param $type
     * @param $name
     * @param $email
     */
    public function __construct($type, $name, $email)
    {
        $this->type = $type;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getSubscriptions()
    {
        if ($this->subscriptions == null) {
            $this->subscriptions = getSubscriptions();
        }
        return $this->subscriptions;
    }
}

function getSubscriptions()
{
    echo "Getting subscriptions...".PHP_EOL;
    usleep(200000);
    return [];
}


$start = microtime(true);

$users = [
    new User("community", "Someone", "some@email.com"),
    new User("community", "Someone", "some@email.com"),
    new User("community", "Someone", "some@email.com"),
    new User("gold", "Someone", "some@email.com"),
    new User("silver", "Someone", "some@email.com")
];

// viewmodel
$viewModelUsers = [];
foreach ($users as $user) {
    $viewModelUser = [];
    if ($user->getType() != 'community') {
        $viewModelUser["subscriptions"] = $user->getSubscriptions();
    } else {
        $viewModelUser["subscriptions"] = [];
    }
    $viewModelUsers[] = $viewModelUser;
}

$stop = microtime(true);
echo($stop-$start). " sec".PHP_EOL;
