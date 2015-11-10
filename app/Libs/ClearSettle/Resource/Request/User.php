<?php

namespace App\Libs\ClearSettle\Resource\Request;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * User Requests
 * 
 * 
 * This class to sends  login request Clear Settle Api..
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
Class User  extends Request
{    
    
    /**
     * Requets
     *
     * @var array
     */
    protected $requests = [
        
        'login'         =>  ['POST' => '/merchant/user/login'],
        'info'          =>  ['POST' => '/merchant/user/info'],
        'create'        =>  ['POST' => '/merchant/user/create'],
        'update'        =>  ['POST' => '/merchant/user/update'],       
        'show'          =>  ['POST' => '/merchant/user/show'],
        'changePassword'=>  ['POST' => '/merchant/user/changePassword'],        
    ];    

        /**
         * To send login request using given user model
         * 
         * @param \Illuminate\Contracts\Auth\Authenticatable $user
         * @param array $credentials
         * @return bool
         */
        public function login(Authenticatable $user, array $credentials)
        {                   
            $this->addOptionsAsParamsForLogin($credentials);
            // sync request, not async !!!
            if ( $this->request('login')->isApproved() ) {
                
                $this->setUser($user);

                $this->storeNewJWTokenOnUser();
                
                return true;
            }   
         
            return false;
        }
        
        
        /**
         * To get user credentials with request options.
         * 
         * @return array        ['email' => value, 'password' => value]
         */
        private function addOptionsAsParamsForLogin(array $credentials)
        {                        
            // References: http://docs.guzzlephp.org/en/latest/request-options.html#form-params
            $params =[
                        'email'     => array_get($credentials, 'email', null),
                        'password'  => array_get($credentials, 'password', null),
                    ];        
            
            $this->putOptions('form_params', $params);
        }        

}
