<?php

class UserService
{
    public function getUsers()
    {
        return [
                [
                    "id" => 1,
                    "name" => "Rob Smith",
                    "other" => "information",
                    "we" => "are",
                    "not" => "interested in"
                ],
                [
                    "id" => 2,
                    "name" => "Helena B. Carter",
                    "other" => "information",
                    "we" => "are",
                    "not" => "interested in"
                ]
            ];
    }
}
