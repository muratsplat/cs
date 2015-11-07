<?php

namespace App\Providers\ClearSettle;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Libs\ClearSettle\Resource\ApiClientManager;

/**
 * This Provider uses Eloquent and ApiClient to verify user credentials.
 * 
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class ApiUserProvider extends EloquentUserProvider implements UserProvider 
{    
    
    /**
     * @var  App\Libs\ClearSettle\Resource\ApiClientManager
     */
    protected $clientManager;
    
        /**
         *  Create a new database and API mixed user provider.
         * 
         * @param ApiClientManager $clients
         * @param string    $model  
         */
        public function __construct(ApiClientManager $clients, $model) 
        {   
            /**
             * We dont need a hasher..
             */
            parent::__construct(null, $model);            
            
            $this->clientManager = $clients;           
        }  

        /**
         * Retrieve a user by the given credentials.
         *
         * @param  array  $credentials
         * @return \Illuminate\Contracts\Auth\Authenticatable|null
         */
        public function retrieveByCredentials(array $credentials) 
        {            
            return null;            
        }

        /**
         * Validate a user against the given credentials.
         *
         * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
         * @param  array  $credentials
         * @return bool
         */
        public function validateCredentials(Authenticatable $user, array $credentials) 
        {
            
            
        }
        
        
        

    
}
