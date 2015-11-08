<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libs\ClearSettle\Resource\ApiClientManager;

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
        // User Repository
        $this->app->bind('App\Contracts\Repository\User', 'App\Repositories\User');
           
        $this->registerClearSettleApiClients();
    }
    
    /**
     * Register ClearSettle Api Client Manager
     * 
     * @return void
     */
    protected function registerClearSettleApiClients()
    {
        $this->app->singleton('app.clearsettle.clients', function($app) {            
            
            return new ApiClientManager($app);    
        });
    }
}
