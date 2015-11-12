<?php

namespace App\Contracts\Auth;

use \Illuminate\Contracts\Auth\Authenticatable;

interface ClearSettleEloquentPayload extends Authenticatable
{     
   
    /**
     * JWT Repository in the app instance
     * 
     * @return \App\Repositories\JSONWebToken
     * @throws \RuntimeException
     */
    public function jwtRepo();
    
    /**
     * To get payload data in user's Clear Settle JWT
     * 
     * @return \stdClass|null
     */
    public function getAuthClearSettlePayload();
    
    /**
     * To get Clear Settle Merchant ID in Clear Settle payload data 
     * 
     * @return int|null
     */
    public function getAuthCSMerchantId();
    
    /**
     * To get Clear Settle merchant user ID in Clear Settle payload data 
     * 
     * @return int|null
     */
    public function getAuthCSMerchantUserId();
    
    /**
     * To get Clear Settle merchant user role in Clear Settle payload data 
     * 
     * @return string|null
     */
    public function getAuthCSMerchantUserRole();
    
    /**
     * To get Clear Settle sub merchant user ids in Clear Settle payload data 
     * 
     * @return array
     */
    public function getAuthCSSubMerchantIds();
    
    /**
     * To get Clear Settle merchant user's time in Clear Settle payload data 
     * 
     * @return \Carbon\Carbon|null
     */
    public function getAuthCSSPayloadTime();
    
    /**
     * Determine if the user has a jwt token for Clear Settle Api
     * 
     * @return bool
     */
    public function authHasCSJWT();
}
