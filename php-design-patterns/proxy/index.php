<?php

class Series implements SeriesInterface {

    private $id;
    private $title;
    private $videos;

    public function __construct($id, $title, $videos) {
        $this->id = $id;
        $this->title = $title;
        $this->videos = $videos;
    }

    public function getVideos() {
        return $this->videos;
    }

    public function getId() {
        return $this->id;
    }
    public function getTitle() {
        return $this->title;
    }

}

interface SeriesInterface {
    function getVideos();
    function getId();
    function getTitle();
}

class SeriesProxy implements SeriesInterface {

    private $db;
    private $original;
    private $videos;

    public function __construct(Series $original, Database $db) {
        $this->original = $original;
        $this->db = $db;
    }

    public function getVideos() {
        if ($this->videos == null) {
            $this->videos = array_map(function($videoRow) {
                return new Video($videoRow["id"], $videoRow["title"]);
            }, $this->db->queryVideos($this->original->getId()));
        }
        return $this->videos;
    }

    public function getId() {
        return $this->original->getId();
    }
    public function getTitle() {
        return $this->original->getTitle();
    }

}

class Video {

    private $id;
    private $title;

    public function __construct($id, $title) {
        $this->id = $id;
        $this->title = $title;
    }

}

class Database {

    public function queryVideos($id) {
        echo 'Querying videos '.$id.PHP_EOL;
        switch($id) {
            case 1:
            $result = [
                    [
                        "id" => 1,
                        "title" => "video1"
                    ]
                ];
            break;
            case 2:
            $result = [
                    [
                        "id" => 2,
                        "title" => "video2"
                    ],
                    [
                        "id" => 3,
                        "title" => "video3"
                    ]
                ];
            break;
        }
        return $result;
    }

    public function querySeries() {
        echo 'Querying series'.PHP_EOL;
        return [
            [
                "id" => 1,
                "title" => "valami"
            ],
            [
                "id" => 2,
                "title" => "valami2"
            ]
        ];

    }

    public function queryJoined() {
        echo 'Querying joined'.PHP_EOL;
        return [
            [
                "id" => 1,
                "title" => "valami",
                "videos" => [
                    [
                        "id" => 1,
                        "title" => "video1"
                    ]
                ]
            ],
            [
                "id" => 2,
                "title" => "valami2",
                "videos" => [
                    [
                        "id" => 2,
                        "title" => "video2"
                    ],
                    [
                        "id" => 3,
                        "title" => "video3"
                    ]
                ]
            ]
                    ];

    }


}

class SeriesRepository {

    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAll() {
        $array = $this->db->querySeries();
        $series = array_map(function($seriesRow) {
            return new SeriesProxy(new Series($seriesRow["id"], $seriesRow["title"], null), $this->db);
        }, $array);
        return $series;
    }

}

$repo = new SeriesRepository(new Database);
$series = $repo->getAll();
$series[0]->getVideos();
$series[0]->getVideos();
$series[1]->getVideos();
$series[1]->getVideos();