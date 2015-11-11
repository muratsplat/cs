<?php

namespace App\Libs\ClearSettle\Resource\Request;

use App\Contracts\Auth\ClearSettleAuthenticatable;
use App\Contracts\Auth\ClearSettleEloquentPayload as JWTPayload;

/**
 * User Requests For Clear Settle Api
 * 
 * Note:
 *  Sended parameters must be validated on Controller !!!
 *  This class does not validate sended parameters.
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
Class User  extends Request
{    
    
    /**
     * Requests
     *
     * @var array
     */
    protected $requests = [
        
        'login'         =>  ['POST' => 'merchant/user/login'],
        'info'          =>  ['POST' => 'merchant/user/info'],
        'create'        =>  ['POST' => 'merchant/user/create'],
        'update'        =>  ['POST' => 'merchant/user/update'],       
        'show'          =>  ['POST' => 'merchant/user/show'],
        'changePassword'=>  ['POST' => 'merchant/user/changePassword'],        
    ]; 

        /**
         * To send login request using given user model
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable; $user
         * @param array $credentials
         * @return bool
         */
        public function login(ClearSettleAuthenticatable $user, array $credentials)
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
         * To create user request to get information of given user
         * 
         * @param \App\Contracts\Auth\ClearSettleEloquentPayload $user
         * @param array $attributes
         * @return bool
         */
        public function create(JWTPayload $user, array $attributes)
        {            
            $this->setUser($user);           
            
            $attributes['merchantId'] = $user->getAuthCSMerchantId();            
            
            $this->putParams($attributes);           
           
            return $this->request('create')->isApproved();           
        }
        
        /**
         * To update user request to get information of given user
         * 
         * @param \App\Contracts\Auth\ClearSettleEloquentPayload $user
         * @param array $attributes
         * @return bool
         */
        public function update(JWTPayload $user, array $attributes)
        {            
            $this->setUser($user);                
            
            $this->putParams($attributes);           
           
            return $this->request('update')->isApproved();           
        }
        
        /** To send info request to get information of given user
         * 
         * @param \App\Contracts\Auth\ClearSettleEloquentPayload $user
         * @param int|null $merchantUserId
         * @return bool
         */
        public function info(JWTPayload $user, $merchantUserId = null)
        {
            $this->setUser($user);
            
            $userId = $merchantUserId ? (integer) $merchantUserId : $user->getAuthCSMerchantUserId();
            // post paremeters
            $params = ['merchantUserId' => $userId];
            
            $this->putParams($params);           
           
            return $this->request('info')->isApproved();           
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
