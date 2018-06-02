<?php

namespace App\Http\ViewModel\Provider;


use App\Http\ViewModel\Factory\LinkFactory;
use App\Http\ViewModel\Link;
use App\Persistence\Model\Post;
use App\Persistence\Repository\PostRepository;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class EloquentPostProvider implements PostProvider
{
    const LIMIT = 20;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var Repository
     */
    private $configRepository;
    /**
     * @var LinkFactory
     */
    private $linkFactory;

    /**
     * EloquentPostProvider constructor.
     * @param PostRepository $postRepository
     * @param Repository $configRepository
     * @param LinkFactory $linkFactory
     */
    public function __construct(PostRepository $postRepository, Repository $configRepository, LinkFactory $linkFactory)
    {
        $this->postRepository = $postRepository;
        $this->configRepository = $configRepository;
        $this->linkFactory = $linkFactory;
    }

    /**
     * Returns the Post elements in a paginator for the main page.
     * @param Request $request
     * @return Paginator
     */
    function retrievePostsForMainPage(Request $request)
    {
        // TODO: Implement retrievePostsForMainPage() method.
    }

    /**
     * Returns the Link elements in an array for the sidebar.
     * @return Link[]
     */
    function retrieveTrendingPosts()
    {
        $baseUrl = $this->configRepository->get("view.main_page.post_base_url");
        $posts = $this->postRepository->findMostViewed(self::LIMIT);
        return array_map(function(Post $post) use($baseUrl) {
            return $this->linkFactory->create($baseUrl.$post->getTitleClean(), $post->getTitle());
        }, $posts);
    }
}