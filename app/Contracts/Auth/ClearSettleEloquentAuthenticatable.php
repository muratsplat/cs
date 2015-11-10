<?php

namespace App\Contracts\Auth;

trait ClearSettleEloquentAuthenticatable
{  
    
    /**
     * Determine if the user is stored.
     *
     * @return bool
     */
    public function getAuthIsExist() 
    {
        return $this->exists;
    }

}
