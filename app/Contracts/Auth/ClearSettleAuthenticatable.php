<?php

namespace App\Contracts\Auth;

interface ClearSettleAuthenticatable
{
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier();
    
    
    /**
     * Determine if the user is stored.
     *
     * @return bool
     */
    public function getAuthIsExist();    
    
}

