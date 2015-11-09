<?php

namespace App\Providers\ClearSettle;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Libs\ClearSettle\Resource\ApiClientManager;
use App\Exceptions\ClearSettle\InvalidCredentialsExc;


/**
 * Clear Settle Api Login Service
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class ApiLoginProvider 
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
         * $param
         */
        public function __construct(ApiClientManager $clients) 
        {   
            $this->clientManager    = $clients;    
        }  
        
        /**
         * To find user by email, if it is not found,
         * create new user.
         * 
         * @param string $email     user email address
         * @return \App\User
         */
        protected function findOrCreateUserByEmail($email)
        {
            return $this->userRepo->findOrCreateByEmail($email);
        }
    
}
