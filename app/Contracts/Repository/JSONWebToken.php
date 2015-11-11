<?php

namespace App\Contracts\Repository;

use \App\Contracts\Auth\ClearSettleAuthenticatable as AuthUser;

/**
 * Interface for JSONWebToken Repository 
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
interface JSONWebToken 
{    
        
        /**
         * To bind jwt token value is
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @param string $jwtToken
         * @return void
         */
        public function storeByUser(AuthUser $user, $jwtToken=null);
        
        /**
         * To get jwt token by given User model
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @param mixed $default
         * @return mixed
         */
        public function getByUser(AuthUser $user, $default = null);
        
        /**
         * Determine if given user's Jwt is stored.
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @return bool
         */
        public function isStoredByUser(AuthUser $user);
        
        /**
         * To get payload in JWT
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @param bool $strict
         * @return \stdClass|null
         */
        public function getPayloadByUser(AuthUser $user, $strict = false);        
        
        /**
         * Determine if Jwt is not stored.
         * 
         * @param \App\Contracts\Auth\ClearSettleAuthenticatable $user
         * @return bool
         */
        public function isNotStoredByUser(AuthUser $user);
}
