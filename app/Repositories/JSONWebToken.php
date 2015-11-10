<?php

namespace App\Repositories;

use App\User as Model;
use InvalidArgumentException;
use App\Contracts\Auth\ClearSettleAuthenticatable as AuthUser;

/**
 * Json Web Token Repository
 * 
 * Notice: this repository is suitable for temporary data
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class JSONWebToken extends Cache
{    
    
    /**
     * Namespace for keys
     * 
     * @var string
     */
    protected $namespace = "\App\Repositories\User\JSONWebToken";
    
    /**
     * Collection Name for keys.
     *
     * @var type 
     */
    protected $collection = "\tokens";


        /**
         * To bind jwt token value is
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @param string $jwtToken
         * @return void
         */
        public function storeByUser(AuthUser $user, $jwtToken=null)
        {            
            $key = $this->generateUniqueKey($user);
            
            $minutes = $this->getExpiration(); 
            
            return $this->cache->put($key, $jwtToken, $minutes);
        }
        
        /**
         * To get jwt token by given User model
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @param mixed $default
         * @return mixed
         */
        public function getByUser(AuthUser $user, $default = null) 
        {
            $key = $this->generateUniqueKey($user); 
            
            return $this->cache->get($key, $default);           
        }  
        
        /**
         * Determine if Jwt is stored.
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @return bool
         */
        public function isStoredByUser(AuthUser $user)
        {
            return (boolean) $this->getByUser($user, null);
        }
        
        /**
         * To generate unique key for giver user
         * 
         * @param App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @return string unique key for given user
         * @throws \InvalidArgumentException
         */
        protected function generateUniqueKey(AuthUser $user)
        {
            $prefix = $this->getPrefixKey();
            
            if ( $user->getAuthIsExist() ) {
                
                $str = $prefix . (string) $user->getAuthIdentifier();
                    
                return md5($str);
            }
            
            throw new InvalidArgumentException('Given user not saved before on db!');           
        }
        
}
