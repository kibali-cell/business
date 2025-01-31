<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $policies = [
        Document::class => DocumentPolicy::class,
    ];    

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
