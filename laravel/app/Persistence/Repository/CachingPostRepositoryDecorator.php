<?php

namespace App\Persistence\Repository;


use Illuminate\Cache\Repository;

class CachingPostRepositoryDecorator extends AbstractPostRepositoryDecorator {

    const ALL_PUBLIC_POST = "allPublicPost";

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
        $cachedValue = $taggedCache->get($key);
        if (is_null($cachedValue)) {
            $cachedValue = $this->postRepository->findAllPublic($page, $size);
            $taggedCache->put($key, $cachedValue);
        }
        return $cachedValue;
    }


}