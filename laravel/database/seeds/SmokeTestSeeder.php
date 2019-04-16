<?php

use Illuminate\Database\Seeder;

class SmokeTestSeeder extends Seeder
{
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
            "enabled" => true
        ]);
        factory(\App\Persistence\Model\Tag::class, 10)->create();
        factory(\App\Persistence\Model\Post::class, 25)->make([
            "enabled" => true
        ])->each(function(\App\Persistence\Model\Post $post) use($category, $author) {
            $post->author()->associate($author);
            $post->save();
            $post->category()->save($category);
        });
    }
}
