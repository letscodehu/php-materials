<?php

namespace App\Persistence\Repository;


use Illuminate\Support\Collection;

interface TagRepository
{

    /**
     * Return collection of Tag with enabled post count
     * ordered by post count desc limited by 20.
     * @return Collection
     */
    function getForTagCloud();

}