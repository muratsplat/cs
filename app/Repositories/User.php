<?php

namespace App\Repositories;

use App\User as Model;
use App\Contracts\Repository\User as UserInterface;

/**
 * User Repository 
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class User  extends Eloquent implements UserInterface
{    
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;   
    
        /**
         * Create User Repository Instance 
         * 
         * @param \App\User $user
         */
        public function __construct(Model $user) 
        {
           $this->model = $user;       
        }        
        
        /**
         * To find user by given email, if it is not found,
         * create new instance..
         * 
         * @param string $email
         * @return \App\User
         */
        public function findOrCreateByEmail($email) 
        {
            $attributes = ['email' => $email];
            return  $this->model->firstOrCreate($attributes);           
        }
        
}
