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
     * @var \App\Libs\ClearSettle\Resource\Request\Use 
     */
    protected $userRequest;    

        /**
         *  Create new Clear Settle Login Service
         * 
         * @param \App\Libs\ClearSettle\Resource\ApiClientManager   $clients
         * @param \App\Contracts\Repository\JSONWebToken            $jwtRepo
         * @param \\App\Libs\ClearSettle\Resource\Request\Use       $userRequest
         */
        public function __construct(ApiClientManager $clients, JSONWebToken $jwtRepo, UserRequest $userRequest) 
        {   
            $this->clientManager    = $clients;   
            
            $this->jwtRepo          = $jwtRepo;
            
            $this->userRequest      = $userRequest;           
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
            $request = $this->userRequest();            
            
            return $request->login($user, $credantials);     
        }       
        
        /**
         * To create new User Request
         * 
         * @return \App\Libs\ClearSettle\Resource\Request\User
         */
        protected function userRequest()
        {
            return $this->userRequest;
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
