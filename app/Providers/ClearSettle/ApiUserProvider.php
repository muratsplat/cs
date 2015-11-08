<?php

namespace App\Providers\ClearSettle;

use App\Contracts\Repository\User as UserRepo;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Libs\ClearSettle\Resource\ApiClientManager;


/**
 * This Provider uses Eloquent and Api Client to verify user credentials.
 * 
 * This provider not saved user password on database or not read password on database
 * via Eloquent. User's password only sended to API by incjected pre-configured http client,
 * and credentials verify job gets action on API server side... 
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
     * @var \App\Contracts\Repository\User 
     */
    protected $userRepo;
    
        /**
         *  Create a new database and API mixed user provider.
         * 
         * @param ApiClientManager $clients
         * @param string    $model  
         * $param
         */
        public function __construct(ApiClientManager $clients, $model, UserRepo $userRepo) 
        {   
            /**
             * We dont need a hasher..
             */
            parent::__construct(null, $model);            
            
            $this->clientManager    = $clients;    
            
            $this->userRepo         = $userRepo;
        }  

        /**
         * Retrieve a user by the given credentials.
         *
         * @param  array  $credentials
         * @return \Illuminate\Contracts\Auth\Authenticatable|null
         */
        public function retrieveByCredentials(array $credentials) 
        {            
               
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
