<?php

namespace App\Http\ViewModel;


class PostPreviewBuilder
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
     * @var string
     */
    private $link;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return PostPreviewBuilder
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param string $published
     * @return PostPreviewBuilder
     */
    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @param string $authorName
     * @return PostPreviewBuilder
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param string[] $categories
     * @return PostPreviewBuilder
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * @param string $excerpt
     * @return PostPreviewBuilder
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return PostPreviewBuilder
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    public function build() {
        return new PostPreview($this);
    }

}