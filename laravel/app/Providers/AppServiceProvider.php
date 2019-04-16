<?php

namespace App\Providers;

use App\Http\ViewFacade\BlogFrontendFacade;
use App\Http\ViewFacade\DefaultBlogFrontendFacade;
use App\Http\ViewModel\Provider\EloquentPostProvider;
use App\Http\ViewModel\Provider\EloquentTagProvider;
use App\Http\ViewModel\Provider\MenuProvider;
use App\Http\ViewModel\Provider\PostProvider;
use App\Http\ViewModel\Provider\StaticMenuProvider;
use App\Http\ViewModel\Provider\TagProvider;
use App\Persistence\Repository\EloquentPostRepository;
use App\Persistence\Repository\EloquentTagRepository;
use App\Persistence\Repository\PostRepository;
use App\Persistence\Repository\TagRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BlogFrontendFacade::class, DefaultBlogFrontendFacade::class);
        $this->app->bind(MenuProvider::class, StaticMenuProvider::class);
        $this->app->bind(PostProvider::class, EloquentPostProvider::class);
        $this->app->bind(PostRepository::class, EloquentPostRepository::class);
        $this->app->bind(TagProvider::class, EloquentTagProvider::class);
        $this->app->bind(TagRepository::class, EloquentTagRepository::class);
    }
}
