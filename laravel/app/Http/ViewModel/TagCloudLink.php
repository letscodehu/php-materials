<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.09.28.
 * Time: 23:16
 */

namespace App\Http\ViewModel;


class TagCloudLink extends Link
{

    private $fontSize;

    /**
     * TagCloudLink constructor.
     * @param $url
     * @param $title
     * @param $fontSize
     */
    public function __construct($url, $title, $fontSize)
    {
        parent::__construct($url, $title);
        $this->fontSize = $fontSize;
    }


    /**
     * @return mixed
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }



}