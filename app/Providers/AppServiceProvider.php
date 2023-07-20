<?php

namespace App\Providers;

use App\Models\Product;
use App\Services\ProductService;
use App\Services\FileBuilderService;
use App\Contracts\FileBuilderInterface;
use App\Repositories\CommentRepository;
use App\Services\AuthenticationService;
use Illuminate\Support\ServiceProvider;
use App\Services\FileBuilderLinuxService;
use App\Contracts\ProductServiceInterface;
use App\Contracts\CommentRepositoryInterface;
use App\Contracts\AuthenticationServiceInterface;
use App\Services\FileBuilderDirector;
use App\Services\FileContentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthenticationServiceInterface::class, AuthenticationService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        // $this->app->when(FileBuilderDirector::class)
        //   ->needs(FileBuilderInterface::class)
        //   ->give(FileBuilderLinuxService::class);
        $this->app->bind(FileBuilderInterface::class, FileBuilderLinuxService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::created(function($product){
            app(FileContentService::class)->setContent($product->name);

            app(FileBuilderDirector::class)->createFileLogger(
                app(FileContentService::class)->simpleContent()
            );
        });
    }
}
