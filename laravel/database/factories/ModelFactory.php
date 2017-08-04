<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Persistence\Model\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Persistence\Model\Post::class, function(\Faker\Generator $faker) {
    return [
        'title' => $faker->title,
        'article' => $faker->text(),
        'title_clean' => $faker->slug,
        'date_published' => $faker->dateTime,
        'banner_image' => $faker->url,
        'featured' => $faker->boolean(),
        'enabled' => $faker->boolean(),
        'comments_enabled' => $faker->boolean(),
        'views' => $faker->numberBetween()
    ];
});

$factory->define(\App\Persistence\Model\Comment::class, function(\Faker\Generator $faker) {
    return [
        'comment' => $faker->text(),
        'is_reply_to' => 0,
        'enabled' => $faker->boolean(),
        'date' => $faker->dateTime
    ];
});

$factory->define(\App\Persistence\Model\Author::class, function(\Faker\Generator $faker) {
    return [
        'display_name' => $faker->userName,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName
    ];
});

$factory->define(\App\Persistence\Model\Category::class, function(\Faker\Generator $faker) {
    return [
        'name' => $faker->text(45),
        'name_clean' => $faker->text(45),
        'enabled' => $faker->boolean(),
        'created_at' => $faker->dateTime
    ];
});

$factory->define(\App\Persistence\Model\Tag::class, function(\Faker\Generator $faker) {
    return [
        'tag' => $faker->text(45),
        'tag_clean' => $faker->text(45),
    ];
});