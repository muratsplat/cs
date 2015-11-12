<?php

namespace App\Http\Middleware;

use Closure;

class UserShouldHasJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( $this->userHasJWT() ) {
            
            return $next($request);          
        }
        
        \Auth::logout();
        
        return redirect()
                ->action('Auth\AuthController@getLogin')
                ->with('neededLogin', 'You need to refresh the session via login again to go on !');       
    }
    
    
    /**
     * Determine if authenticated user has JWT Token for Clear Settle API
     * 
     * @return boolean
     */
    protected function userHasJWT()
    {
        $user = \Auth::getUser();
            
        if ( is_null($user) ) {
            
            return false;
        }        
       
        if ($user instanceof \App\Contracts\Auth\ClearSettleEloquentPayload)  {            
            
            return $user->authHasCSJWT();
        }

        return false;
    }
}
