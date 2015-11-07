<?php

namespace App\Libs\ClearSettle\Resource\Request;

use Exception;


/**
 * Login Request
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
      //method              http verb   route
        'login'         =>  ['POST' => '/merchant/user/login'],
        'get'           =>  ['POST' => '/merchant/user/get'],
        'info'          =>  ['POST' => '/merchant/user/info'],
        'update'        =>  ['POST' => '/merchant/user/update'],       
        'show'          =>  ['POST' => '/merchant/user/show'],
        'changePassword'=>  ['POST' => '/merchant/user/changePassword'],        
    ];    

        /**
         * To send login request using injected User Object
         * 
         * @return stdClass|null
         */
        public function login()
        {                   
            $options    = $this->getFormParamsForLogin();
            // sync request, not async !!!
            if ( $this->request('login', $options)->isApproved() ) {

                return $this->convertResponseBodyToJSON(); 
            }   

            if ( $this->isReady() && $this->isJSON() ) {

                return $this->convertResponseBodyToJSON();                                    
            }               
         
            return null;
        }
        
        
        /**
         * To get user credentials with request options.
         * 
         * @return array
         */
        private function getFormParamsForLogin()
        {            
            list($email, $password) = $this->getUserCredentials();
            
            // References: http://docs.guzzlephp.org/en/latest/request-options.html#form-params
            return [
                        'form_params' =>
                            
                            [
                                'email'     => $email,
                                'password'  => $password,
                            ]
                
                    ];           
        }
        
        /**
         * To get user Credentials
         * 
         * @return array    [email, password]
         */
        private function getUserCredentials()
        {
            return [$this->user->getAuthEmail(), $this->user->getAuthPassword()];
        }
    
      
}
