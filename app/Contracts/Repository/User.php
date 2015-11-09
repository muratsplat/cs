<?php

namespace App\Contracts\Repository;

/**
 * Interface for User Repository 
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
interface User extends Base  
{    
        
        /**
         * To find user by given email, if it is not found,
         * create new instance..
         * 
         * @param string $email
         * @return \App\User
         */
        public function findOrCreateByEmail($email);
}
