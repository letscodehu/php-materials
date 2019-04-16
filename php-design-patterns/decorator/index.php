<?php

interface UserRepository {

    public function getAll();
    public function retreiveOne($id);

}

class SqlUserRepository implements UserRepository {

    public function getAll() {
        sleep(1);
        return [
            [
                "id" => 1,
                "name" => "Rob Griggs"
            ],
            [
                "id" => 2,
                "name" => "Helena B. Carter"
            ]
        ];
    }

    public function retreiveOne($id) {
        sleep(1);
        return [
            "id" => 2,
            "name" => "Helena B. Carter"
        ]; 
    }

}

abstract class AbstractUserRepositoryDecorator implements UserRepository {

    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getAll() {
        return $this->userRepository->getAll();
    }
    public function retreiveOne($id) {
        return $this->userRepository->retreiveOne($id);
    }

}

class Cache {

    private $storage = [];

    public function has($key) {
        return array_key_exists($key, $this->storage);
    }

    public function put($key, $value) {
        $this->storage[$key] = $value;
    }

    public function get($key) {
        return $this->storage[$key];
    }

}

class CachingUserRepositoryDecorator extends AbstractUserRepositoryDecorator {

    private $cache;
    const ALLUSERS = "allUsers";

    public function __construct(UserRepository $userRepository, Cache $cache) {
        parent::__construct($userRepository);
        $this->cache = $cache;
    }

    public function getAll() {
        if (!$this->cache->has(self::ALLUSERS)) {
            $this->cache->put(self::ALLUSERS,
            $this->userRepository->getAll());
        }
        return $this->cache->get(self::ALLUSERS);
    }

}

$userRepository = new CachingUserRepositoryDecorator(new SqlUserRepository(), new Cache());

var_dump($userRepository->retreiveOne(1));
var_dump($userRepository->retreiveOne(1));