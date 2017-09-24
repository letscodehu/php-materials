<?php

namespace App\Providers;

use App\Http\ViewFacade\BlogFrontendFacade;
use App\Http\ViewFacade\DefaultBlogFrontendFacade;
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
    }
}
