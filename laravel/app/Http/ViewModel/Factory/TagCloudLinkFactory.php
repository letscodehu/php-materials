<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.11.28.
 * Time: 20:49
 */

namespace App\Http\ViewModel\Factory;


use App\Http\ViewModel\TagCloudLink;

class TagCloudLinkFactory
{

    /**
     * @param $url
     * @param $title
     * @param $fontSize
     * @return TagCloudLink
     */
    public function create($url, $title, $fontSize) {
        return new TagCloudLink($url, $title, $fontSize);
    }

}