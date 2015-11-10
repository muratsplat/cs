<?php

namespace App\Providers;

use App\Services\ClearSettle\ApiLogin;
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
        
        // JWT Token Repository
        $this->app->bind('App\Contracts\Repository\JSONWebToken', 'App\Repositories\JSONWebToken');
        
        $this->registerClearSettleApiLogin();
        
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
    
        
    /**
     * Register ClearSettle Api Login Service
     * 
     * @return void
     */
    protected function registerClearSettleApiLogin()
    {        
        $this->app->singleton('app.clearsettle.login', function($app) {            
            
            $jwtRepo = $app->make('App\Contracts\Repository\JSONWebToken');
            
            return new ApiLogin($app['app.clearsettle.clients'], $jwtRepo);    
        });
    }
}
