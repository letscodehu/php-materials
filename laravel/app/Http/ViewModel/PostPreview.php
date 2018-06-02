<?php

namespace App\Http\ViewModel;


class PostPreview
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $published;

    /**
     * @var string
     */
    private $authorName;

    /**
     * @var string[]
     */
    private $categories;

    /**
     * @var string
     */
    private $excerpt;

    /**
     * @var Link
     */
    private $link;

    public function __construct(PostPreviewBuilder $builder)
    {
        $this->title = $builder->getTitle();
        $this->published = $builder->getPublished();
        $this->authorName = $builder->getAuthorName();
        $this->categories = $builder->getCategories();
        $this->excerpt = $builder->getExcerpt();
        $this->link = $builder->getLink();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @return string[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * @return Link
     */
    public function getLink()
    {
        return $this->link;
    }

    public static function builder() {
        return new PostPreviewBuilder();
    }


}