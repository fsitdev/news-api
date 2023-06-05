<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NewsAPIService;
use App\Services\NYTimesService;
use App\Services\TheGuardianService;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('newsapi', function ($app) {
            $apiKey = config('services.news_api_key.newsapi');
            return new NewsApiService($apiKey);
        });

        $this->app->bind('nytimes', function ($app) {
            $apiKey = config('services.news_api_key.nytimes');

            return new NYTimesService($apiKey);
        });

        $this->app->bind('theguardian', function ($app) {
            $apiKey = config('services.news_api_key.theguardian');

            return new TheGuardianService($apiKey);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
