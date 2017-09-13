<?php

namespace App\Persistence\Repository;


use Illuminate\Cache\Repository;

class CachingPostRepositoryDecorator extends AbstractPostRepositoryDecorator {

    const ALL_PUBLIC_POST = "allPublicPost";
    const MOST_VIEWED = "mostViewed";
    const CATEGORY = "category";

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
        $key = sprintf("page_%s:%s", $page, $size);
        $cachedValue = $this->cacheFunctionResult($taggedCache, $key, 60, function()
        use ($page, $size) {
            return $this->postRepository->findAllPublic($page, $size);
        });
        return $cachedValue;
    }

    public function findMostViewed($limit)
    {
        $taggedCache = $this->cacheRepository->tags(self::MOST_VIEWED);
        $key = sprintf("limit:%s", $limit);
        $cachedValue = $this->cacheFunctionResult($taggedCache, $key, 60, function()
        use ($limit) {
            return $this->postRepository->findMostViewed($limit);
        });
        return $cachedValue;
    }

    public function findByCategory($slug, $page, $size)
    {
        $taggedCache = $this->cacheRepository->tags(self::CATEGORY);
        $key = sprintf("%s:%s:%s", $slug, $page, $size);
        $cachedValue = $this->cacheFunctionResult($taggedCache, $key, 60, function()
            use ($slug, $page, $size) {
            return $this->postRepository->findByCategory($slug, $page, $size);
        });
        return $cachedValue;
    }

    private function cacheFunctionResult(Repository $storage, $cachekey, $ttl, callable $callable) {
        $cachedValue = $storage->get($cachekey);
        if (is_null($cachedValue)) {
            $cachedValue = $callable();
            $storage->put($cachekey, $cachedValue, $ttl);
        }
        return $cachedValue;
    }

    public function findByTag($slug, $page, $size)
    {
        $taggedCache = $this->cacheRepository->tags("tag");
        $key = sprintf("%s:%s:%s", $slug, $page, $size);
        $cachedValue = $this->cacheFunctionResult($taggedCache, $key, 60, function()
        use ($slug, $page, $size) {
            return $this->postRepository->findByTag($slug, $page, $size);
        });
        return $cachedValue;
    }


}