<?php

class Account {
    public int $id;
    public int $version;
    public string $address;
}

class AccountService {
    private PDO $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function find(int $id) : Account {
        $stmt = $this->pdo->prepare("SELECT * FROM account WHERE id = :id");
        $stmt->execute(["id" => $id]);
        return $stmt->fetchObject(Account::class);
    }

    public function update(Account $account) {
        $stmt = $this->pdo->prepare("UPDATE account SET address = :address, version = (version + 1) WHERE id = :id AND version = :version");
        $stmt->execute(["id" => $account->id, "address" => $account->address, "version" => $account->version]);
        if ($stmt->rowCount() == 0) {
            throw new Exception("An update happened since you started editing this account.");
        }
    }
}


$pdo = new PDO("sqlite:training.db");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE IF NOT EXISTS account (id int, address varchar, version int)");
$pdo->exec("INSERT INTO ACCOUNT (id, address, version) VALUES (1, '1114 Budapest Valami út 5', 0)");

$marika = new AccountService($pdo);
$peter = new AccountService($pdo);

$mAccount = $marika->find(1);
$pAccount = $peter->find(1);

$pAccount->address = "1115 Budapest Valami út 4";
$mAccount->address = "1115 Budapest Valami út 4 2.em 19";

$marika->update($mAccount);
$peter->update($pAccount);

var_dump($marika->find(1));
