<?php

namespace App\Http\ViewModel\Provider;


use App\Http\ViewModel\Link;
use Illuminate\Contracts\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

interface PostProvider
{

    /**
     * Returns the Post elements in a paginator for the main page.
     * @param Request $request
     * @return Paginator
     */
    function retrievePostsForMainPage(Request $request);

    /**
     * Returns the Link elements in an array for the sidebar.
     * @return Link[]
     */
    function retrieveTrendingPosts();

}