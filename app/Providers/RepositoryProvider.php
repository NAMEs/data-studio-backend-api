<?php

namespace App\Providers;

use App\Repository\ColorMapRepository;
use App\Repository\ConnectionStringRepository;
use App\Repository\DataSourceRepository;
use App\Repository\PageRepository;
use App\Repository\ElementRepository;
use App\Repository\QueryParamRepository;
use App\Repository\QueryRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider {
    private $repositories = [
        ConnectionStringRepository::class,
        DataSourceRepository::class,
        ElementRepository::class,
        QueryParamRepository::class,
        QueryRepository::class,
        ColorMapRepository::class,
        PageRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
//        foreach ($this->repositories as $Repository) {
//            $this->app->singleton($Repository, function ($app) use ($Repository) {
//                return new $Repository();
//            });
//        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        //
    }
}
