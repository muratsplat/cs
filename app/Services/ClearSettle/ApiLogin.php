<?php

namespace App\Services\ClearSettle;

use App\Contracts\Repository\JSONWebToken;
use App\Contracts\Auth\ClearSettleAuthenticatable;
use App\Libs\ClearSettle\Resource\Request\User  as UserRequest;
use App\Libs\ClearSettle\Resource\ApiClientManager;

/**
 * Clear Settle Api Login Service
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class ApiLogin
{    
    
    /**
     * @var \App\Libs\ClearSettle\Resource\ApiClientManager
     */
    protected $clientManager;
    
    /**
     * @var \App\Contracts\Repository\JSONWebToken
     */
    protected $jwtRepo;
    

        /**
         *  Create new Clear Settle Login Service
         * 
         * @param \App\Libs\ClearSettle\Resource\ApiClientManager   $clients
         * @param \App\Contracts\Repository\JSONWebToken            $jwtRepo
         */
        public function __construct(ApiClientManager $clients, JSONWebToken $jwtRepo ) 
        {   
            $this->clientManager    = $clients;   
            
            $this->jwtRepo          = $jwtRepo;
        }  
       
        /**
         * To login Api Service
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @param array $credantials
         * @return bool
         */
        public function login(ClearSettleAuthenticatable $user, array $credantials)
        {
            $request = $this->createNewUserRequest();            
            
            return $request->login($user, $credantials);     
        }       
        
        /**
         * To create new User Request
         * 
         * @return \App\Libs\ClearSettle\Resource\Request\User
         */
        protected function createNewUserRequest()
        {
            $client = $this->createApiClient();
            
            $jwt    = $this->jwtRepo;
            
            return new UserRequest($client, $jwt);
        }
        
        /**
         * Create pre-configured http client for Clear Settle Api request
         * 
         * @return \GuzzleHttp\Client
         */
        protected function createApiClient()
        {
            return $this->clientManager->newClient();
        }        
    
}
