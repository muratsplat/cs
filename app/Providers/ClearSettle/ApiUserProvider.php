<?php

namespace App\Providers\ClearSettle;

use App\Contracts\Repository\User           as UserRepo;
use App\Services\ClearSettle\ApiLogin       as Login;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use App\Contracts\Auth\ClearSettleAuthenticatable;
use App\Libs\ClearSettle\Resource\ApiClientManager;
use App\Exceptions\ClearSettle\InvalidCredentialsExc;
use Illuminate\Contracts\Auth\Authenticatable;


/**
 * This Provider uses Eloquent and Api Client to verify user credentials.
 * 
 * This provider does not save user password or not read password on database
 * via Eloquent. User's password gets from user request, and than pasword and email 
 * send to API by incjected pre-configured http client,
 * and credentials verify job gets action on API server side... 
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class ApiUserProvider extends EloquentUserProvider implements UserProvider 
{    
    
    /**
     * @var  \App\Libs\ClearSettle\Resource\ApiClientManager
     */
    protected $clientManager;
    
    /**
     * @var \App\Contracts\Repository\User 
     */
    protected $userRepo;
    
    /**
     * @var \App\Services\ClearSettle\ApiLogin
     */
    protected $login;
    
         /**
         *  Create a new database and API mixed user provider.
         * 
         * @param \App\Libs\ClearSettle\Resource\ApiClientManager   $clients
         * @param \App\Contracts\Repository\User                    $userRepo  
         * @param \App\Services\ClearSettle\ApiLogin                $login
         */
        public function __construct(ApiClientManager $clients, UserRepo $userRepo, Login $login) 
        {              
            $this->clientManager    = $clients;    
            
            $this->userRepo         = $userRepo;
            
            $this->login            = $login;
        }  

        /**
         * Retrieve a user by the given credentials.
         *
         * @param  array  $credentials
         * @return \Illuminate\Contracts\Auth\Authenticatable|null
         */
        public function retrieveByCredentials(array $credentials) 
        {            
            $user       = $this->getUserByCredentials($credentials);
           
            if ($this->loginByApi($user, $credentials)) {
                
                return $user;
            }
            
           
            
            return null;
        }
        
        /**
         * To login given user by Clear Settle Login Service which incjected the app
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @param array $credentials
         * @return bool
         */
        protected function loginByApi(ClearSettleAuthenticatable $user, array $credentials)
        {
            return $this->login->login($user, $credentials);
        }
        
        /**
         * To get User model by given credentials
         * 
         * @param array $credentials
         * @return \Illuminate\Contracts\Auth\Authenticatable
         * @throws \App\Exceptions\ClearSettle\InvalidCredentialsExc
         */
        private function getUserByCredentials(array $credentials)
        {            
            $email      = array_get($credentials, 'email', null);
            
            $password   = array_get($credentials, 'password', null);
            
            if (is_null($password) || is_null($email)) {
                
                throw new InvalidCredentialsExc('e-mail or password segments are required !');               
            }
            
            return $this->findOrCreateUserByEmail($email);           
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
            return $this->loginByApi($user, $credentials);            
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
