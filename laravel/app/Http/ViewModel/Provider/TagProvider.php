<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.10.10.
 * Time: 21:44
 */

namespace App\Http\ViewModel\Provider;


interface TagProvider
{
    public function retrieveTagCloud();
}