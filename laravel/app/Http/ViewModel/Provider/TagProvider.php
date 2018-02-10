<?php

namespace App\Http\ViewModel\Provider;

use App\Http\ViewModel\TagCloud;

/**
 * Provides Tag related viewmodels.
 */
interface TagProvider
{
    /**
     * Returns a tagcloud for the sidebar.
     * @return TagCloud
     */
    function retrieveTagCloud();
}