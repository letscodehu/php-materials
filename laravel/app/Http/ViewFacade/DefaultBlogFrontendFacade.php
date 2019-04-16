<?php


namespace App\Http\ViewFacade;


use App\Http\ViewModel\MainPageModel;
use App\Http\ViewModel\Provider\MenuProvider;
use App\Http\ViewModel\Provider\PostProvider;
use App\Http\ViewModel\Provider\TagProvider;
use App\Http\ViewModel\SinglePostModel;
use Illuminate\Contracts\Config\Repository;
use Symfony\Component\HttpFoundation\Request;

class DefaultBlogFrontendFacade implements BlogFrontendFacade
{
    const VIEW_MAIN_PAGE_FACEBOOK_URL = "view.main_page.facebook_url";
    const VIEW_MAIN_PAGE_TWITTER_URL = "view.main_page.twitter_url";
    const VIEW_MAIN_PAGE_FEED_URL = "view.main_page.feed_url";
    const APP_ANALYTICS_KEY = "app.analytics_key";

    /**
     * @var MenuProvider
     */
    private $menuProvider;

    /**
     * @var PostProvider
     */
    private $postProvider;
    /**
     * @var TagProvider
     */
    private $tagProvider;
    /**
     * @var Repository
     */
    private $configRepository;

    /**
     * DefaultBlogFrontendFacade constructor.
     * @param $menuProvider MenuProvider
     * @param $postProvider PostProvider
     * @param TagProvider $tagProvider
     * @param Repository $configRepository
     */
    public function __construct(MenuProvider $menuProvider, PostProvider $postProvider,
        TagProvider $tagProvider, Repository $configRepository)
    {
        $this->menuProvider = $menuProvider;
        $this->postProvider = $postProvider;
        $this->tagProvider = $tagProvider;
        $this->configRepository = $configRepository;
    }


    function assembleMainPageModel(Request $request)
    {
        return MainPageModel::builder()
            ->setMenu($this->menuProvider->provide())
            ->setContent($this->postProvider->retrievePostsForMainPage($request))
            ->setTrending($this->postProvider->retrieveTrendingPosts())
            ->setTagCloud($this->tagProvider->retrieveTagCloud())
            ->setFacebookUrl($this->configRepository->get(self::VIEW_MAIN_PAGE_FACEBOOK_URL))
            ->setTwitterUrl($this->configRepository->get(self::VIEW_MAIN_PAGE_TWITTER_URL))
            ->setFeedUrl($this->configRepository->get(self::VIEW_MAIN_PAGE_FEED_URL))
            ->setAnalyticsKey($this->configRepository->get(self::APP_ANALYTICS_KEY))
            ->build();
    }

    function assembleSinglePostModel($year, $month, $day, $hour, $minute, $second, $postSlug)
    {
        return new SinglePostModel($this->menuProvider->provide());
    }
}