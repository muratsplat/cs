<?php

namespace App\Repositories;

use App\User;

/**
 * User Repository
 * 
 * 
 * TODO: 
 *  Adds Caching Support
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class User
{
    
    /**
     * @var \App\User 
     */
    protected $model;   
    
        /**
         * Create new User Repository
         * 
         * @param \App\User $user
         */
        public function __construct(User $user) 
        {            
            $this->model = $user;
        }

        /**
         * To create new model
         * 
         * @param array $attributes
         * @return \App\User
         */
        public function create(array $attributes) 
        {
            return $this->model->create($attributes);
        }        
        
        /**
         * To update exist model or if it is not exist,
         * create new one
         * 
         * @param array $attributes
         * @return \App\User
         */
        public function updateOrCreate(array $attributes) 
        {            
            return $this->model->updateOrCreate($attributes);
        }
        
        /**
         * To delete model looking given id 
         * 
         * @param int $id
         * @return boolean
         */
        public function delete($id)
        {
            $model = $this->find($id);
            
            if (is_null($model)) { return false; }
            
            return $model->delete();
        }

        /**
         * To update model
         * 
         * @param int $id
         * @param array $attributes
         * @return bool|int
         */
        public function update($id, array $attributes)
        {
            $model = $this->find($id);
            
            if (is_null($model)) { return false; }
            
            return $model->update($attributes);            
        }

        /**
         * To find model 
         * 
         * @param int $id
         * @return \App\User|null
         */
        public function find($id)
        {            
            return $this->model->newQuery()->find($id);
        }
}
