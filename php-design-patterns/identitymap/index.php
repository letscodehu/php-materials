<?php

class Customer
{
    public bool $notificationEnabled;
    public int $id;
    public string $name;
}


$pdo = new PDO("sqlite:training.db");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$pdo->exec("CREATE TABLE IF NOT EXISTS customer (id int, name varchar, notification_enabled bool)");
$pdo->exec("INSERT INTO customer (id, name, notification_enabled) VALUES (1, 'Demo', true)");


class Repository
{
    private array $identityMap = [];
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function findCustomer(int $id): Customer
    {
        if (array_key_exists($id, $this->identityMap)) {
            return $this->identityMap[$id];
        }
        $stmt = $this->pdo->prepare("SELECT id, name, notification_enabled as notificationEnabled FROM customer WHERE id = :id");
        $stmt->execute(["id" => $id]);
        $customer = $stmt->fetchObject(Customer::class);
        $this->identityMap[$id] = $customer;
        return $customer;
    }
}

class UserUpdateService
{

    private Repository $repo;
    public function __construct(Repository $repo)
    {
        $this->repo = $repo;
    }

    public function unsubscribe(int $id)
    {
        $customer = $this->repo->findCustomer($id);
        $customer->notificationEnabled = false;
    }
}

class NotificationService
{

    private Repository $repo;
    public function __construct(Repository $repo)
    {
        $this->repo = $repo;
    }

    public function notify(int $id)
    {
        $customer = $this->repo->findCustomer($id);
        if ($customer->notificationEnabled) {
            echo "Notification sent to ".$customer->name.PHP_EOL;
        }
    }
}


$repo = new Repository($pdo);
$updateService = new UserUpdateService($repo);
$nofificationService = new NotificationService($repo);

$updateService->unsubscribe(1);
$nofificationService->notify(1);

