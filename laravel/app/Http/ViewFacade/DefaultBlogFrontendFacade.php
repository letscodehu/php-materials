<?php


namespace App\Http\ViewFacade;


use App\Http\ViewModel\MainPageModel;
use App\Http\ViewModel\Provider\MenuProvider;
use App\Http\ViewModel\Provider\PostProvider;
use Symfony\Component\HttpFoundation\Request;

class DefaultBlogFrontendFacade implements BlogFrontendFacade
{

    /**
     * @var MenuProvider
     */
    private $menuProvider;

    /**
     * @var PostProvider
     */
    private $postProvider;

    /**
     * DefaultBlogFrontendFacade constructor.
     * @param $menuProvider MenuProvider
     * @param $postProvider PostProvider
     */
    public function __construct(MenuProvider $menuProvider, PostProvider $postProvider)
    {
        $this->menuProvider = $menuProvider;
        $this->postProvider = $postProvider;
    }


    function assembleMainPageModel(Request $request)
    {
        return MainPageModel::builder()
            ->setMenu($this->menuProvider->provide())
            ->setContent($this->postProvider->retrievePostsForMainPage($request))
            ->setTrending($this->postProvider->retrieveTrendingPosts())
            ->build();
    }
}