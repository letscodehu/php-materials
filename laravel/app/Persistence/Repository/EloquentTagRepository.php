<?php

namespace App\Persistence\Repository;


use App\Persistence\Model\Tag;
use Illuminate\Support\Collection;

class EloquentTagRepository implements TagRepository
{

    /**
     * @var Tag
     */
    private $model;

    /**
     * EloquentTagRepository constructor.
     * @param Tag $model
     */
    public function __construct(Tag $model)
    {
        $this->model = $model;
    }

    /**
     * Return collection of Tag with enabled post count
     * ordered by post count desc limited by 20.
     * @return Collection
     */
    function getForTagCloud()
    {
        return $this->model
            ->query()
            ->withCount(["posts" => function($query) {
                $query->where("enabled", true);
            }])
            ->orderBy("posts_count", "desc")
            ->limit(20)
            ->get();
    }
}