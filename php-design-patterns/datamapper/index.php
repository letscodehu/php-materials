<?php

class Order {

    private $id;
    private $total;
    private $billingCim;
    private $billingCity;
    private $billingName;

    /**
     * Order constructor.
     * @param $id
     * @param $total
     * @param $billingAddress
     * @param $billingCity
     * @param $billingName
     */
    public function __construct($id, $total, $billingAddress, $billingCity, $billingName)
    {
        $this->id = $id;
        $this->total = $total;
        $this->billingCim = $billingAddress;
        $this->billingCity = $billingCity;
        $this->billingName = $billingName;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getBillingCim()
    {
        return $this->billingCim;
    }

    /**
     * @return mixed
     */
    public function getBillingCity()
    {
        return $this->billingCity;
    }

    /**
     * @return mixed
     */
    public function getBillingName()
    {
        return $this->billingName;
    }

}

class OrderMapper {

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * OrderMapper constructor.
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
        $record = $statement->fetch();
        if ($record) {
            return $this->mapFromRecord($record);
        }
    }

    public function save(Order $order) {
        if ($this->find($order->getId())) {
            return $this->update($order);
        } else {
            return $this->create($order);
        }
    }


    private function mapFromRecord($record)
    {
        return new Order($record["id"], $record["total"], $record["billing_address"], $record["billing_city"], $record["billing_name"]);
    }

    private function update(Order $order)
    {
        $statement = $this->pdo->prepare("UPDATE orders SET total = :total, billing_city = :billing_city,
            billing_name = :billing_name, billing_address = :billing_address WHERE id = :id");
        $statement->execute($this->mapToParameters($order));
        return $order;
    }

    private function mapToParameters(Order $order)
    {
        return [
            "id" => $order->getId(),
            "total" => $order->getTotal(),
            "billing_address" => $order->getBillingCim(),
            "billing_city" => $order->getBillingCity(),
            "billing_name" => $order->getBillingName()
        ];
    }

    private function create(Order $order)
    {
        $newOrder = new Order(uniqid(true), $order->getTotal(), $order->getBillingCim(), $order->getBillingCity(), $order->getBillingName());
        $statement = $this->pdo->prepare("INSERT INTO orders (id, total, billing_city, billing_name, billing_address) 
            VALUES (:id, :total, :billing_city, :billing_name, :billing_address)");
        $statement->execute($this->mapToParameters($newOrder));
        return $newOrder;
    }

}

$pdo = new PDO("mysql:hostname=localhost;dbname=training", "training", "password");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$orderMapper = new OrderMapper($pdo);

$new = new Order(null, 5000, "address", "city", "name");

$saved = $orderMapper->save($new);

$orderMapper->save(new Order($saved->getId(), 500, "address", "city", "name"));

$order = $orderMapper->find($saved->getId());
var_dump($order);