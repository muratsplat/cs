<?php

namespace App\Contracts\Auth;

use Carbon\Carbon;
use RuntimeException;
use ReflectionException;


trait ClearSettleEloquentPayloadAble
{     
   
    /**
     * JWT Repository in the app instance
     * 
     * @return \App\Repositories\JSONWebToken
     * @throws \RuntimeException
     */
    public function jwtRepo() 
    {
        try {
            
            $repo = \app('App\Repositories\JSONWebToken');            
            
        } catch (ReflectionException $e) {
            
            throw new RuntimeException('JSONWebToken Repository instance not resolved !');            
        }

        return $repo;
    }    
    
    /**
     * To get payload data in user's Clear Settle JWT
     * 
     * @return \stdClass|null
     */
    public function getAuthClearSettlePayload()
    {
        $repo = $this->jwtRepo();
        
        return $repo->getPayloadByUser($this);        
    }
    
    /**
     * To get Clear Settle Merchant ID in Clear Settle payload data 
     * 
     * @return int|null
     */
    public function getAuthCSMerchantId()
    {       
        $payload =  $this->getAuthClearSettlePayload();   
        
        return is_null($payload) ? null : (integer) $payload->merchantId;
    }
    
    /**
     * To get Clear Settle merchant user ID in Clear Settle payload data 
     * 
     * @return int|null
     */
    public function getAuthCSMerchantUserId()
    {       
        $payload =  $this->getAuthClearSettlePayload();   
        
        return is_null($payload) ? null : (integer) $payload->merchantUserId;
    }    
    
    /**
     * To get Clear Settle merchant user role in Clear Settle payload data 
     * 
     * @return string|null
     */
    public function getAuthCSMerchantUserRole()
    {       
        $payload =  $this->getAuthClearSettlePayload();   
        
        return is_null($payload) ? null : $payload->role;
    }
    
    /**
     * To get Clear Settle sub merchant user ids in Clear Settle payload data 
     * 
     * @return array
     */
    public function getAuthCSSubMerchantIds()
    {       
        $payload =  $this->getAuthClearSettlePayload();   
        
        return is_null($payload) ? array() : $payload->subMerchantIds;
    }
    
    /**
     * To get Clear Settle merchant user's time in Clear Settle payload data 
     * 
     * @return \Carbon\Carbon|null
     */
    public function getAuthCSSPayloadTime()
    {       
        $payload =  $this->getAuthClearSettlePayload(); 
        
         if ( is_null($payload) ) { 
             
             return  null;
         }
         
         $timeStamp = $payload->timestamp;
         
         if ( is_null($timeStamp)) {
             
             return null;
         }
         
         return Carbon::createFromTimestamp($timeStamp);
    }
    
    /**
     * Determine if the user has a jwt token for Clear Settle Api
     * 
     * @return bool
     */
    public function authHasCSJWT()
    {       
        return $this->jwtRepo()->isStoredByUser($this);
    }
}
