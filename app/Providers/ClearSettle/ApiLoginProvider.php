<?php

namespace App\Providers\ClearSettle;

use App\Repositories\User;
use App\Libs\ClearSettle\Resource\Request\User  as UserRequest;
use App\Libs\ClearSettle\Resource\ApiClientManager;

/**
 * Clear Settle Api Login Service
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class ApiLoginProvider 
{    
    
    /**
     * @var \App\Libs\ClearSettle\Resource\ApiClientManager
     */
    protected $clientManager;
    
    /**
     *
     * @var \App\Repositories\User 
     */
    protected $repo;    
    
        /**
         *  Create a new Clear Settle Login Service
         * 
         * @param ApiClientManager $clients
         * @param \App\Repositories\User    $repo
         */
        public function __construct(ApiClientManager $clients, User $repo) 
        {   
            $this->clientManager    = $clients;   
            
            $this->repo             = $repo;
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
        
        public function validateCredentials(array $credantials)
        {
            
            
        }
        
        
        protected function createUserRequest()
        {
            return new UserRequest($user, $client);
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
