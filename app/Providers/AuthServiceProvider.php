<?php

namespace App\Providers;

use App\Providers\ClearSettle\ApiUserProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        //parent::registerPolicies($gate);
     

        \Auth::extend('clearSettleApi', function($app)  {

            $userRepo       = $app->make('App\Contracts\Repository\User');

            $loginService   = $app->make('app.clearsettle.login');  
            
            return new ApiUserProvider($app['app.clearsettle.clients'], $userRepo,$loginService);
        });
    }   

}
