<?php

namespace App\Http\ViewModel\Factory;


use App\Http\ViewModel\Link;

class LinkFactory
{

    /**
     * @param $url
     * @param $title
     * @return Link
     */
    public function create($url, $title) {
        return new Link($url, $title);
    }


}