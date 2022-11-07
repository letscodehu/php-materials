<?php

class UserListFactory
{
    public function create(array $users)
    {
        return array_map(function ($user) {
            return [
                "id" => ">".$user["id"],
                "name" => $user["name"]. "<"
            ];
        }, $users);
    }
}
