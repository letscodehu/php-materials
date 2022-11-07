<?php

class MessageSource
{
    private $messages = [
        "dashboard.users.title" => "Dashboard - Users"
    ];

    public function get($key)
    {
        return array_key_exists($key, $this->messages) ? $this->messages[$key] : "!!". $key. "!!";
    }
}
