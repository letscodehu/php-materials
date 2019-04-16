<?php 


class RegisterController {

    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

}

interface UserRepository {
    public function save(UserRepresentation $userEntity);
}

abstract class UserRepresentation {

}

class UserEntity extends UserRepresentation {

}

class UserDocument extends UserRepresentation {

}

class SqlUserRepository implements UserRepository {

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

}

class MongoUserRepository implements UserRepository {
    
}