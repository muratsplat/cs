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
        
        $model = \Config::get('auth.model');
        
        $userRepo = $this->app->make('App\Contracts\Repository\User');

        \Auth::extend('ClearSettleApi', function($app) use ($model, $userRepo) {
            
                return new ApiUserProvider($app['app.clearsettle.clients'], $model, $userRepo);
        });
    }
}
