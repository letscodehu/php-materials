<?php

class Account
{
    public int $id;
    public string $address;
}

class MySqlAccountService implements AccountService
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find(int $id): Account
    {
        $stmt = $this->pdo->prepare("SELECT * FROM account WHERE id = :id");
        $stmt->execute(["id" => $id]);
        return $stmt->fetchObject(Account::class);
    }

    public function update(Account $account)
    {
        $stmt = $this->pdo->prepare("UPDATE account SET address = :address WHERE id = :id");
        $stmt->execute(["id" => $account->id, "address" => $account->address]);
    }
}

interface AccountService
{
    function find(int $id);
    function update(Account $account);
}

class LockingAccountService implements AccountService
{

    private AccountService $accountService;
    private PDO $pdo;
    private int $owner;

    public function __construct(AccountService $accountService, int $owner, PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->accountService = $accountService;
        $this->owner = $owner;
    }
    public function find(int $id)
    {
        $this->releaseAllLocks($this->owner);
        $this->tryLock($id);
        return $this->accountService->find($id);
    }
    public function update(Account $account)
    {
        $this->accountService->update($account);
        $this->releaseAllLocks($this->owner);
    }
    private function releaseAllLocks()
    {
        $stmt = $this->pdo->prepare("DELETE FROM lock WHERE owner = :owner");
        $stmt->execute(["owner" => $this->owner]);
    }
    private function tryLock(int $resource)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM lock WHERE id = :id");
        $stmt->execute(["id" => $resource]);
        if ($stmt->fetch() !== false) {
            throw new RuntimeException("Already locked!");
        }
        $stmt = $this->pdo->prepare("INSERT INTO lock (id, owner) VALUES (:id,:owner)");
        $stmt->execute(["id" => $resource, "owner" => $this->owner]);
    }
}

$pdo = new PDO("sqlite:training.db");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE IF NOT EXISTS lock (id int, owner int)");
$pdo->exec("CREATE TABLE IF NOT EXISTS account (id int, address varchar)");
$pdo->exec("INSERT INTO ACCOUNT (id, address) VALUES (1, '1114 Budapest Valami út 5')");

$marika = new LockingAccountService(new MySqlAccountService($pdo), 1, $pdo);
$peter = new LockingAccountService(new MySqlAccountService($pdo), 2, $pdo);

$mAccount = $marika->find(1);
$mAccount->address = "1115 Budapest Valami út 4 2.em 19";
$marika->update($mAccount);

$pAccount = $peter->find(1);
$pAccount->address = "1115 Budapest Valami út 4";
$peter->update($pAccount);

var_dump($marika->find(1));
