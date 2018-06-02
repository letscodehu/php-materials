<?php 

interface UserDao {
    function getAll();
}

class SimpleUserDao implements UserDao {

    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getAll() {
        return $this->userRepository->getAll();
    }

}

class CachingUserDao implements UserDao {

    private $userRepository;
    private $cache;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getAll() {
        if (!$this->cache) {
            $this->cache = 'valami';
            return $this->userRepository->getAll();
        } else {
            echo 'Getting users from cache'.PHP_EOL;
        }
    }
}

interface UserRepository {
    function getAll();
}

class MySqlUserRepository implements UserRepository {

    function getAll() {
        echo 'Getting users from MySQL'.PHP_EOL;
    }
}

class MongoDBUserRepository implements UserRepository {

    function getAll() {
        echo 'Getting users from MongoDB'.PHP_EOL;
    }
}

class CassandraUserRepository implements UserRepository {

    function getAll() {
        echo 'Getting users from Cassandra'.PHP_EOL;
    }
}

$cachingMongoUserDao = new SimpleUserDao(new CassandraUserRepository);
$cachingMongoUserDao->getAll();
$cachingMongoUserDao->getAll();

$cachingMySqlUserDao = new CachingUserDao(new MySqlUserRepository);
$cachingMySqlUserDao->getAll();
$cachingMySqlUserDao->getAll();

$mongoUserDao = new SimpleUserDao(new MongoDBUserRepository);
$mongoUserDao->getAll();

$mySqlUserDao = new SimpleUserDao(new MySqlUserRepository);
$mySqlUserDao->getAll();