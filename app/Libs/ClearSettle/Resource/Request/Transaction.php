<?php

namespace App\Libs\ClearSettle\Resource\Request;

use App\Contracts\Auth\ClearSettleAuthenticatable;
use App\Contracts\Auth\ClearSettleEloquentPayload as JWTPayload;

/**
 * Transaction Requests For Clear Settle Api
 * 
 * Note:
 *  Sended parameters must be validated on Controller !!!
 *  This class does not validate sended parameters.
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
Class Transaction  extends Request
{    
    
    /**
     * Requests
     *
     * @var array
     */
    protected $requests = [
        
        'report'        =>  ['POST' => 'transactions/report'],
        'list'          =>  ['POST' => 'transactions/list'],
        'get'           =>  ['POST' => 'transactions/get'],      
    ];         
        
        /**
         * To report request to get information of given user
         * 
         * @param \App\Contracts\Auth\ClearSettleEloquentPayload $user
         * @param  string   $fromDate    'YYYY-MM-DD'
         * @param  string   $toDate      'YYY-MM-DD'
         * @param  int      $merchantId   
         * @param  int      $acquirer
         * @return bool
         */
        public function report(JWTPayload $user, $fromDate, $toDate, $merchantId=null, $acquirer=null)
        {            
            $this->setUser($user);           
            
            $params['merchant'] = is_null($merchantId) ? $user->getAuthCSMerchantId(): $merchantId; 
            
            $params['fromDate'] = $fromDate;
            
            $params['toDate']   = $toDate;
            
            $params['acquirer'] = $acquirer;
            
            $this->putParams($params);           
           
            return $this->request('report')->isApproved();           
        }
        
}
