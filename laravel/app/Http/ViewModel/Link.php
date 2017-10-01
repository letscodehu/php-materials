<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.09.28.
 * Time: 23:15
 */

namespace App\Http\ViewModel;

/**
 * Holds the information needed to render a link.
 */
class Link
{

    protected $url;
    protected $title;

    /**
     * Link constructor.
     * @param $url
     * @param $title
     */
    public function __construct($url, $title)
    {
        $this->url = $url;
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }


}