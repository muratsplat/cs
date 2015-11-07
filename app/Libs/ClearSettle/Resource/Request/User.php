<?php

namespace App\Libs\ClearSettle\Resource\Request;

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
        public function login(array $credentials)
        {                   
            $options    = $this->getFormParamsForLogin($credentials);
            // sync request, not async !!!
            if ( $this->request('login', $options)->isApproved() ) {

                $res =  $this->convertResponseBodyToJSON();
                
                $this->setJWTTokenOnUser($res);
                
                return $res;
            }   

            if ( $this->isReady() && $this->isJSON() ) {

                return $this->convertResponseBodyToJSON();                                    
            }               
         
            return null;
        }
        
        
        /**
         * To get user credentials with request options.
         * 
         * @return array        ['email' => value, 'password' => value]
         */
        private function getFormParamsForLogin(array $credentials)
        {                        
            // References: http://docs.guzzlephp.org/en/latest/request-options.html#form-params
            return [
                        'form_params' =>
                            
                            [
                                'email'     => array_get($credentials, 'email', null),
                                'password'  => array_get($credentials, 'password', null),
                            ]
                
                    ];           
        }
        
        
        /**
         * To set JWT token on user by given decoded json reponse
         * 
         * @param \stdClass $jsonObject
         */
        protected function setJWTTokenOnUser(\stdClass $jsonObject)
        {
            if ( isset($jsonObject->token) ) {
                
                $this->user->setJWTToken($jsonObject->token);
            }            
        }
}
