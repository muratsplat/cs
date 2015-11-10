<?php

namespace App\Contracts\Auth;

/**
 * An Interface to login Clear Settle Api Service for user
 * 
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
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

