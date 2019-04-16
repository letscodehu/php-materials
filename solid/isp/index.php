<?php 

interface CreatingRepository {
    function create($entity);
}

interface CrudRepository extends CreatingRepository, QueryingRepository, ModifyingRepository {

}


interface ModifyingRepository {
    function deleteById($id);   
    function update($id, $entity);
}

interface QueryingRepository {
    function count();
    function findAll();
    function findById($id);
}


class UserRepository implements CrudRepository  {

    private $content = [];

    function count() {
        return count($this->content);
    }
    function deleteById($id) {
        if ($this->contains($id)) {
            unset($this->content[$id]);
            echo "Deleted '$id' index". PHP_EOL;
        }
    }
    function findAll() {
        return $this->content;
    }
    function findById($id) {
        if ($this->contains($id)) {
            echo "Found elem with '$id'". PHP_EOL;
            return $this->content[$id];
        }
    }
    function update($id, $entity) {
        if ($this->contains($id)) {
            $this->content[$id] = $entity;
            echo "Updated '$id' with '$entity'". PHP_EOL;
        }
    }
    function create($entity) {
        $this->content[] = $entity;
        end($this->content);
        echo 'Inserted index was '. key($this->content).PHP_EOL;
    }

    private function contains($id) {
        return array_key_exists($id, $this->content);
    }

}

class TransactionRepository implements CreatingRepository, QueryingRepository {
    
    private $content = [];

    function count() {
        return count($this->content);
    }
    function findAll() {
        return $this->content;
    }
    function findById($id) {
        if ($this->contains($id)) {
            echo "Found elem with '$id'". PHP_EOL;
            return $this->content[$id];
        }
    }
    function create($entity) {
        $this->content[] = $entity;
        end($this->content);
        echo 'Inserted index was '. key($this->content).PHP_EOL;
    }

    private function contains($id) {
        return array_key_exists($id, $this->content);
    }

}

$repository = new UserRepository;

$repository->create("test1");
$repository->create("test2");
$repository->create("test3");

$repository->deleteById(5);
$repository->deleteById(2);

$repository->update(1, "jó");

$repository->findById(2);
$repository->findById(0);

var_dump($repository->findAll());