<?php

namespace App\Repositories;


/**
 * Eloquent Repository 
 * 
 * TODO: 
 *  Adds Caching Support
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
abstract class Eloquent 
{
    
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;   
    

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
        
        /**
         * To get all 
         * 
         * @param array $columns
         * @return \Illuminate\Database\Eloquent\Collection|static[]
         */
        public function all($columns = ['*'])
        {            
            return $this->model->all($columns);
        }
        
        /**
         * To get model
         * 
         * @return \Illuminate\Database\Eloquent\Model
         */
        public function getModel()
        {
            return $this->model;
        }
        
        /**
         * To get number of models.
         * 
         * @return int
         */
        public function count() 
        {
            return $this->all(['id'])->count();
        }
}
