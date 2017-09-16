<?php

namespace App\Persistence\Repository;


use Illuminate\Cache\Repository;

class CachingPostRepositoryDecorator extends AbstractPostRepositoryDecorator {

    const ALL_PUBLIC_POST = "allPublicPost";
    const PAGE_KEY_TEMPLATE = "page_%s:%s";
    const DEFAULT_CACHE_TTL = 60;
    const MOST_VIEWED = "mostViewed";

    /**
     * @var PostRepository
     */
    protected $postRepository;
    /**
     * @var Repository
     */
    private $cacheRepository;

    /**
     * @param PostRepository $postRepository
     * @param $cacheRepository
     */
    function __construct($postRepository, $cacheRepository)
    {
        $this->postRepository = $postRepository;
        $this->cacheRepository = $cacheRepository;
    }

    public function findAllPublic($page, $size)
    {
        $taggedCache = $this->cacheRepository->tags(self::ALL_PUBLIC_POST);
        $key = sprintf(self::PAGE_KEY_TEMPLATE, $page, $size);
        return $this->cacheFunctionResult($taggedCache, $key, self::DEFAULT_CACHE_TTL, function()
        use ($page, $size) {
            return $this->postRepository->findAllPublic($page, $size);
        });
    }

    public function findMostViewed($limit)
    {
        $taggedCache = $this->cacheRepository->tags(self::MOST_VIEWED);
        $key = sprintf("post:%s", $limit);
        return $this->cacheFunctionResult($taggedCache, $key, self::DEFAULT_CACHE_TTL, function()
            use ($limit) {
            return $this->postRepository->findMostViewed($limit);
        });
    }

    public function findByCategory($slug, $page, $size)
    {
        $taggedCache = $this->cacheRepository->tags("category");
        $key = sprintf("%s:%s:%s", $slug, $page, $size);
        return $this->cacheFunctionResult($taggedCache, $key, self::DEFAULT_CACHE_TTL, function()
            use ($slug, $page, $size) {
            return $this->postRepository->findByCategory($slug, $page, $size);
        });
    }

    public function findByTag($slug, $page, $size)
    {
        $taggedCache = $this->cacheRepository->tags("tag");
        $key = sprintf("%s:%s:%s", $slug, $page, $size);
        return $this->cacheFunctionResult($taggedCache, $key, self::DEFAULT_CACHE_TTL, function()
        use ($slug, $page, $size) {
            return $this->postRepository->findByTag($slug, $page, $size);
        });
    }

    private function cacheFunctionResult(Repository $storage, $cacheKey, $ttl, callable $callable) {
        $cachedValue = $storage->get($cacheKey);
        if (is_null($cachedValue)) {
            $cachedValue = $callable();
            $storage->put($cacheKey, $cachedValue, $ttl);
        }
        return $cachedValue;
    }


}