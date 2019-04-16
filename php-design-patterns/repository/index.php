<?php
declare(strict_types=1);

class UserService {

    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getUserByEmail(string $email) : User {
        return $this->userRepository->getByEmail($email);
    }

}

interface UserRepository {

    function getByEmail(string $email) : User;

}

class User {

    private $id;    
    private $name;
    private $email;

    public function __construct($id, $name, $email) {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
    }

}

class SqlDatabase {

    public function querySingle(string $sqlString, array $paramArray) : array {
        return [
            "id" => 5,
            "name" => "Krisztian Sql",
            "email" => "krisztian@letscode.hu"
        ];
    }

}

class Mongo {

    public function find(array $paramArray) {
        return [
            "id" => 6,
            "name" => "Krisztian Mongo",
            "email" => "krisztian@letscode.hu"
        ];
    }

}


class FakeUserRepository implements UserRepository {

    public function getByEmail(string $email) : User {
        return new User(4, "Krisztian Fake", $email);
    }

}

class MongoUserRepository implements UserRepository {

    private $db;

    public function __construct(Mongo $db) {
        $this->db = $db;
    }

    public function getByEmail(string $email) : User {
        $result = $this->db->find(["email" => $email]);
        return $result == null ? null : new User($result["id"], $result["name"], $result["email"]);
    }

}


class SqlUserRepository implements UserRepository {

    private $db;

    public function __construct(SqlDatabase $db) {
        $this->db = $db;
    }

    public function getByEmail(string $email) : User {
        $result = $this->db->querySingle("SELECT * FROM users WHERE email = :email", ["email" => $email]);
        return $result == null ? null : new User($result["id"], $result["name"], $result["email"]);
    }

}

$service = new UserService(new MongoUserRepository(new Mongo));
$user = $service->getUserByEmail("krisztian@letscode.hu");
var_dump($user);
