<?php

namespace App\Contracts\Repository;

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
         * @param \App\User $user
         * @param string $jwtToken
         * @return void
         */
        public function storeByUser(Model $user, $jwtToken=null);
        
        /**
         * To get jwt token by given User model
         * 
         * @param \App\User $user
         * @param mixed $default
         * @return mixed
         */
        public function getByUser(Model $user, $default = null);
        
        /**
         * Determine if given user's Jwt is stored.
         * 
         * @param \App\User $user
         * @return bool
         */
        public function isStoredByUser(Model $user);
}
