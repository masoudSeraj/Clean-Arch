<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\FileBuilderService;
use App\Services\FileContentService;
use App\Services\FileBuilderDirector;
use App\Contracts\FileBuilderInterface;
use App\Contracts\FileContentInterface;
use App\Repositories\CommentRepository;
use App\Services\AuthenticationService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Services\FileBuilderLinuxService;
use App\Contracts\ProductServiceInterface;
use Illuminate\Support\Facades\RateLimiter;
use App\Contracts\CommentRepositoryInterface;
use App\Contracts\AuthenticationServiceInterface;

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
        
        $this->app->singleton(FileContentInterface::class, FileContentService::class);

        $this->app->singleton(FileBuilderInterface::class, FileBuilderLinuxService::class);
        
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::created(function($product){
            $filecontent = app(FileContentInterface::class);
            $filecontent->setContent($product->name);
            $filecontent->setFilename('products.txt');

            app(FileBuilderDirector::class)->createFileLogger(
                $filecontent->simpleContent()
            );
        });

        RateLimiter::for('throttler', function (Request $request) {
            return Limit::perMinutes(config('parspack.throttle', 100), 10)->by($request->user()?->id ?: $request->ip())->response(function (Request $request, array $headers) {
                return response([
                    'Too many tries please wait ' . $headers['Retry-After'] 
                ], 429, $headers);
            });
        });
    }
}
