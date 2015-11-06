<?php

namespace App\Libs\ClearSettle\Resource\Request;

//use Exception;


/**
 * Login Request
 * 
 * This class to sends  login request Clear Settle Api..
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
Class Login  extends Request
{   
    
    /**
     * The url for login request
     *
     * @var string 
     */
    protected $login = '/merchant/user/login';
    
    /**
     * The url for merchant information.
     *
     * @var string 
     */
    protected $get = '/merchant/user/get';

    
        public function login()
        {
            $options    = $this->getFormParamsForLogin();
            
            $response   = $this->request('POST', $this->login, $options);
                
            $status     = $response->getStatusCode();
            if ( $response->getStatusCode() === 200
        }
        
        
        /**
         * 
         * @return type
         */
        protected function getFormParamsForLogin()
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
