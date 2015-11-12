<?php

namespace App\Repositories;


/**
 * Simple Repository for CRUD jobs in Clear Settle API
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class UserApi extends Api 
{   
    
    
    /**
     * User
     *
     * @var \App\Contracts\Auth\ClearSettleAuthenticatable
     */
    protected $user;
    
    
        /**
         * To create new request
         *            
         * @return \App\Libs\ClearSettle\Resource\Request\User
         */
        public function createNewRequest()
        {            
            return $this->clientManager->createNewRequest('user');
        }    

        
//        /api/v3/merchant/user/create Create a new merchant user.
//        /api/v3/merchant/user/update Update information of merchant user.
//        /api/v3/merchant/user/show Show details of merchant user.
//        /api/v3/merchant/user/info
//          /api/v3/merchant/user/changePassword Change password of merchant user.
//          /api/v3/merchant/user/list Requests for list of merchant user.
//          /api/v3/merchant/user/delete
        
        
        /**
         * To create user
         * 
         * @param array $attributes
         * @return boolean
         */
        public function create(array $attributes)
        {
            $request = $this->createNewRequest();
            
            $user       = $this->getUser();
            
            $this->setRequest($request);
            
            return $request->create($user, $attributes);        
        }
        
        /**
         * To update user
         * 
         * @param array $attributes
         * @return boolean
         */
        public function update(array $attributes)
        {
            $request    = $this->createNewRequest();
            
            $user       = $this->getUser();
            
            $this->setRequest($request);
            
            return $request->update($user, $attributes);
        }
        
        /**
         * To show user
         * 
         * @param int $merchantUserId
         * @return boolean
         */
        public function show($merchantUserId)
        {
            $request    = $this->createNewRequest();
            
            $user       = $this->getUser();
            
            $this->setRequest($request);
            
            return $request->show($user, $merchantUserId);
        }  
        
        /**
         * To get info of given user
         * 
         * @param int $merchantUserId
         * @return boolean
         */
        public function info($merchantUserId)
        {
            $request    = $this->createNewRequest();
            
            $user       = $this->getUser();
            
            $this->setRequest($request);
            
            return $request->info($user, $merchantUserId);
        } 
        
        /**
         * To change password user
         * 
         * @param array $attributes
         * @return boolean
         */
        public function changePassword(array $attributes)
        {
            $request    = $this->createNewRequest();
            
            $user       = $this->getUser();
            
            $this->setRequest($request);
            
            return $request->info($user, $attributes);
        }
        
         /**
         * To delete given user identifier
         * 
         * @param int $merchantUserIdentifier
         * @return boolean
         */
        public function delete($merchantUserIdentifier)
        {
            $request    = $this->createNewRequest();
            
            $user       = $this->getUser();
            
            $this->setRequest($request);
            
            return $request->delete($user, $merchantUserIdentifier);
        }
}
