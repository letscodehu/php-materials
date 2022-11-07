<?php

class Account
{
    private string $id;
    private string $name;
    public function setName(string $name)
    {
        $this->name = $name;
        EntityManager::getInstance()->registerDirty($this);
    }
    public static function create(string $name)
    {
        $account = new self();
        $account-> id = uniqid("", true);
        $account->name = $name;
        EntityManager::getInstance()->registerNew($account);
        return $account;
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function remove(): void
    {
        EntityManager::getInstance()->registerRemoved($this);
    }
}

class AccountMapper
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find(string $id): Account
    {
        $stmt = $this->pdo->prepare("SELECT * FROM account WHERE id = :id");
        $stmt->execute(["id" => $id]);
        $account = $stmt->fetchObject(Account::class);
        return $account;
    }
}
class EntityManager
{
    private static $instance = null;
    private array $new = [];
    private array $dirty = [];
    private array $removed = [];
    private function __construct()
    {
    }
    public function flush()
    {
        $this->insertNew();
        $this->updateDirty();
        $this->deleteRemoved();
        var_dump($this);
    }

    private function insertNew()
    {
    }
    private function updateDirty()
    {
    }
    private function deleteRemoved()
    {
    }
    public function registerRemoved(object $entity)
    {
        $id = $entity->getId();
        assert($id != null, "id cannot be null");
        if (array_key_exists($id, $this->new)) {
            unset($this->new[$id]);
            return;
        }
        if (array_key_exists($id, $this->dirty)) {
            unset($this->dirty[$id]);
        }
        if (!array_key_exists($id, $this->removed)) {
            $this->removed[$id] = $entity;
        }
    }
    public function registerDirty(object $entity)
    {
        $id = $entity->getId();
        assert($id != null, "id cannot be null");
        assert(!array_key_exists($id, $this->removed), "cannot be deleted");
        if (!array_key_exists($id, $this->new) && !in_array($entity, $this->dirty)) {
            $this->dirty[$id] = $entity;
        }
    }
    public function registerNew(object $entity)
    {
        $id = $entity->getId();
        assert($id != null, "id cannot be null");
        assert(!array_key_exists($id, $this->removed), "cannot be dirty");
        assert(!array_key_exists($id, $this->removed), "cannot be deleted");
        assert(!array_key_exists($id, $this->new), "cannot be already registered as new");
        $this->new[$id] = $entity;
    }
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

$pdo = new PDO("sqlite:training.db");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE IF NOT EXISTS account (id string, name varchar)");
$pdo->exec("INSERT INTO ACCOUNT (id, name) VALUES ('some random id', 'Test account')");
$pdo->exec("INSERT INTO ACCOUNT (id, name) VALUES ('some other id', 'Account to be deleted')");

$mapper = new AccountMapper($pdo);
$dirty = $mapper->find("some random id");
$dirty->setName("dirtiest");
$new = Account::create("new");
$deletable = $mapper->find("some other id");
$deletable->remove();
EntityManager::getInstance()->flush();
