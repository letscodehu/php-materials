<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.10.01.
 * Time: 17:28
 */

namespace App\Http\ViewModel\Provider;


use App\Http\ViewModel\Menu;

interface MenuProvider
{

    /**
     * Returns a Menu built for the sidebar.
     * @return Menu
     */
    function provide();

}