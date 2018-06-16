<?php

namespace App\Http\ViewModel\Transformer;


use App\Http\ViewModel\PostPreview;
use App\Persistence\Model\Post;

class PostPreviewTransformer
{

    /**
     * @var ExcerptTransformer
     */
    private $excerptTransformer;
    /**
     * @var PostLinkTransformer
     */
    private $postLinkTransformer;

    /**
     * PostPreviewTransformer constructor.
     * @param ExcerptTransformer $excerptTransformer
     * @param PostLinkTransformer $postLinkTransformer
     */
    public function __construct(ExcerptTransformer $excerptTransformer, PostLinkTransformer $postLinkTransformer)
    {
        $this->excerptTransformer = $excerptTransformer;
        $this->postLinkTransformer = $postLinkTransformer;
    }


    /**
     * Transforms a Post to a PostPreview
     * @param Post $post
     * @return PostPreview
     */
    public function transform(Post $post) {
        return PostPreview::builder()
            ->setTitle($post->getTitle())
            ->setPublished($post->getDatePublished())
            ->setExcerpt($this->excerptTransformer->transform($post->getArticle()))
            ->setAuthorName($post->author->getDisplayName())
            ->setCategories($post->category->map(function($item) {
                return $item->name_clean;
            })->toArray())
            ->setLink($this->postLinkTransformer->transform($post->getTitleClean()))
            ->build();
    }

}