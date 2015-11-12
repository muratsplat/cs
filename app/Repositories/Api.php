<?php

namespace App\Repositories;

use Exception;
use InvalidArgumentException;
use App\Contracts\Repository\JSONWebToken           as JWTRepo;
use App\Contracts\Auth\ClearSettleAuthenticatable   as CSAuth;
use App\Libs\ClearSettle\Resource\ApiClientManager;


/**
 * Simple Repository for CRUD jobs in Clear Settle API
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
abstract class Api 
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
     * @var \App\Libs\ClearSettle\Resource\Request\Request 
     */
    protected $request;
        
        /**
         * Create RepositoryInstance
         * 
         * @param \App\Libs\ClearSettle\Resource\ApiClientManager $manager
         * @param \App\Contracts\Repository\JSONWebToken  $jwtRepo
         */
        public function __construct(ApiClientManager $manager, JWTRepo $jwtRepo) 
        {            
            $this->clientManager    = $manager;
            
            $this->jwtRepo          = $jwtRepo;
        }          
                
        /**
         * set user
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         */
        public function setUser(CSAuth $user)
        {
            $this->user = $user;            
        }
        
        /**
         * Get User 
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable
         * @throws \Exception
         */
        public function getUser() {
            
            $user = $this->user;
            
            if (is_null($user)) {
                
                throw new Exception('The user is required to send request for api !');
                
            }
            
            return $user;
        }        
        
        /**
         * To get equest
         * 
         * @return \App\Libs\ClearSettle\Resource\Request\Request|null
         */
        public function getRequest()
        {
            return $this->request;            
        }
        
        /**
         * To set request
         * 
         * @param \App\Libs\ClearSettle\Resource\Request\Request $request
         * @throws \InvalidArgumentException
         */
        protected function setRequest($request)
        {
            if (is_subclass_of($request,'App\Libs\ClearSettle\Resource\Request\Request')) {
                
                $this->request = $request;
                return;
            }
            
            throw new InvalidArgumentException('Invalid request object!');                        
        }
                
}
