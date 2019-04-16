<?php

namespace App\Http\ViewModel\Transformer;


/**
 *  Transforms an articles content into a proper excerpt.
 */
class ExcerptTransformer
{
    const MORE_TAG = "<!-- MORE -->";

    /**
     * @param $article
     * @return string the excerpt
     */
    public function transform($article) {
        $moreTagIndex = strpos($article, self::MORE_TAG);
        if ($moreTagIndex > 0) {
            $excerpt = substr($article, 0, $moreTagIndex);
        } else {
            $excerpt = $article;
        }
        return $excerpt;
    }

}