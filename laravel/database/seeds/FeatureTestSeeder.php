<?php

use Illuminate\Database\Seeder;

class FeatureTestSeeder extends Seeder
{

    private $titleCounter = 0;
    private $titleCleanCounter = 0;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $author = factory(\App\Persistence\Model\Author::class)->create(
            [
                "display_name" => "John Doe"
            ]
        );
        $category = factory(\App\Persistence\Model\Category::class)->create([
            "name" => "PHP",
            "name_clean" => "php",
            "enabled" => true
        ]);
        factory(\App\Persistence\Model\Tag::class, 10)->create();
        factory(\App\Persistence\Model\Post::class, 25)->make([
            "enabled" => true,
            "title" => function() { return $this->nextTitle(); },
            "title_clean" => function() { return $this->nextTitleClean(); },
            "article" => "excerpt<!-- MORE -->left of the article",
            "date_published" => new DateTime("2018-08-17 16:20:00")
        ])->each(function(\App\Persistence\Model\Post $post) use($category, $author) {
            $post->author()->associate($author);
            $post->save();
            $post->category()->save($category);
        });
    }

    private function nextTitle() {
        return "Title ".$this->titleCounter++;
    }

    private function nextTitleClean()
    {
        return "title-".$this->titleCleanCounter++;
    }
}
