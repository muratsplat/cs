<?php

namespace App\Contracts\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * An Interface to login Clear Settle Api Service for user
 * 
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
interface ClearSettleAuthenticatable extends Authenticatable
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

