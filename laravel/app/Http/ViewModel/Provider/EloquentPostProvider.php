<?php

namespace App\Http\ViewModel\Provider;


use App\Http\ViewModel\Factory\LinkFactory;
use App\Http\ViewModel\Link;
use App\Http\ViewModel\Transformer\PostPreviewTransformer;
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
     * @var PostPreviewTransformer
     */
    private $postPreviewTransformer;

    /**
     * EloquentPostProvider constructor.
     * @param PostRepository $postRepository
     * @param Repository $configRepository
     * @param LinkFactory $linkFactory
     * @param PostPreviewTransformer $postPreviewTransformer
     */
    public function __construct(PostRepository $postRepository, Repository $configRepository, LinkFactory $linkFactory,
            PostPreviewTransformer $postPreviewTransformer)
    {
        $this->postRepository = $postRepository;
        $this->configRepository = $configRepository;
        $this->linkFactory = $linkFactory;
        $this->postPreviewTransformer = $postPreviewTransformer;
    }

    /**
     * Returns the Post elements in a paginator for the main page.
     * @param Request $request
     * @return Paginator
     */
    function retrievePostsForMainPage(Request $request)
    {
        $paginator = $this->postRepository->findAllPublic($request->get('page'), $request->get('size'));
        $previews = collect($paginator->items())->map(function(Post $post) {
            return $this->postPreviewTransformer->transform($post);
        });
        return new \Illuminate\Pagination\Paginator($previews, $request->get('size'), $request->get('page'));
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