<?php

namespace App\Http\ViewModel\Provider;


use App\Http\ViewModel\Factory\TagCloudLinkFactory;
use App\Http\ViewModel\TagCloud;
use App\Persistence\Model\Tag;
use App\Persistence\Repository\TagRepository;
use Illuminate\Contracts\Config\Repository;

class EloquentTagProvider implements TagProvider
{

    /**
     * @var TagRepository
     */
    private $repository;
    /**
     * @var Repository
     */
    private $configRepository;
    /**
     * @var TagCloudLinkFactory
     */
    private $tagCloudLinkFactory;

    /**
     * EloquentTagProvider constructor.
     * @param TagRepository $repository
     * @param Repository $configRepository
     * @param TagCloudLinkFactory $tagCloudLinkFactory
     */
    public function __construct(TagRepository $repository, Repository $configRepository,
                                TagCloudLinkFactory $tagCloudLinkFactory)
    {
        $this->repository = $repository;
        $this->configRepository = $configRepository;
        $this->tagCloudLinkFactory = $tagCloudLinkFactory;
    }

    public function retrieveTagCloud()
    {
        $baseUrl = $this->configRepository->get("view.main_page.tag_base_url");
        $links = $this->repository->getForTagCloud()
            ->map(function (Tag $item) use ($baseUrl) {
                return $this->tagCloudLinkFactory
                    ->create($baseUrl . $item->getSlug(),
                        $item->getTitle(),
                        $item->getPostsCount());
            });
        return new TagCloud($links->toArray());
    }
}