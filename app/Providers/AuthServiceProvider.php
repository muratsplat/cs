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
        
        $class = \Config::get('auth.model');

        \Auth::extend('ClearSettleApi', function($app) use ($class) {
            
                return new ApiUserProvider($app['app.clearsettle.clients'], $class);
        });
    }
}
