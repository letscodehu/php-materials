<?php

class OrderTableDataGateway {

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * OrderTableDataGateway constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find($id) {
        $statement = $this->pdo->prepare("SELECT id, total, billing_city, billing_name, billing_address FROM orders WHERE id = :id");
        $statement->execute([
            "id" => $id
        ]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function save(array $order) {
        if (array_key_exists("id", $order) && $this->find($order["id"])) {
            return $this->update($order);
        } else {
            return $this->create($order);
        }
    }

    private function update(array $order)
    {
        $statement = $this->pdo->prepare("UPDATE orders SET total = :total, billing_city = :billing_city,
            billing_name = :billing_name, billing_address = :billing_address WHERE id = :id");
        $statement->execute($order);
        return $order;
    }

    private function create(array $order)
    {
        $order["id"] = uniqid(true);
        $statement = $this->pdo->prepare("INSERT INTO orders (id, total, billing_city, billing_name, billing_address) 
                VALUES (:id, :total, :billing_city, :billing_name, :billing_address)");
        $statement->execute($order);
        return $order;
    }

}

$pdo = new PDO("mysql:hostname=localhost;dbname=training", "training", "password");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$orderGateway = new OrderTableDataGateway($pdo);

$order = [
    "total" => 500,
    "billing_address" => "address",
    "billing_city" => "city",
    "billing_name" => "name"
];

$saved = $orderGateway->save($order);

$order = $orderGateway->find($saved["id"]);
var_dump($order);
