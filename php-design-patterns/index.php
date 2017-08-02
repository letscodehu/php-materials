<?php

class LandingSummary {

    private $topSeries;
    private $latestVideos;
    private $latestPosts;
    private $totalLength;

    public function __construct(LandingSummaryBuilder $builder) {
        $this->latestPosts = $builder->getLatestPosts();
        $this->latestVideos = $builder->getLatestVideos();
        $this->topSeries = $builder->getTopSeries();
        $this->totalLength = $builder->getTotalLength();
    }

    public function getTopSeries() {
        return $this->topSeries;
    }

    public function getTotalLength() {
        return $this->totalLength;
    }

    public function getLatestPosts() {
        return $this->latestPosts;
    }

    public function getLatestVideos() {
        return $this->latestVideos;
    }

    public static function builder() {
        return new LandingSummaryBuilder();
    }

}

class LandingSummaryBuilder {

    private $topSeries;
    private $latestVideos;
    private $totalLength;
    private $latestPosts;

    public function setTopSeries($topSeries) {
        $this->topSeries = $topSeries;
        return $this;
    }

    public function setLatestVideos($latestVideos) {
        $this->latestVideos = $latestVideos;
        return $this;
    }

    public function setLatestPosts($latestPosts) {
        $this->latestPosts = $latestPosts;
        return $this;
    }

    public function setTotalLength($totalLength) {
        $this->totalLength = $totalLength;
        return $this;
    }

    public function getTopSeries() {
        return $this->topSeries;
    }

     public function getLatestPosts() {
        return $this->latestPosts;
    }

    public function getLatestVideos() {
        return $this->latestVideos;
    }

    public function getTotalLength() {
        return $this->totalLength;
    }

    public function build() {
        return new LandingSummary($this);
    }

}

$landingSummary = LandingSummary::builder()
    ->setTotalLength("totalLength")
    ->setTopSeries("topSeries")
    ->setLatestPosts("latestPosts")
    ->setLatestVideos("latestVideos")
    ->build();

var_dump($landingSummary);