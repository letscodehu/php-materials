<?php

namespace App\Http\ViewFacade;


use Symfony\Component\HttpFoundation\Request;

interface BlogFrontendFacade
{

    function assembleMainPageModel(Request $request);

}