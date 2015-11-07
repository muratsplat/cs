<?php

namespace App\Contracts\Repository;

/**
 * Base Interface for repositories
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
interface Base 
{    
        /**
         * To create new model
         * 
         * @param array $attributes
         * @return \Illuminate\Database\Eloquent\Model
         */
        public function create(array $attributes);
        
        /**
         * To update exist model or if it is not exist,
         * create new one
         * 
         * @param array $attributes
         * @return \Illuminate\Database\Eloquent\Model
         */
        public function updateOrCreate(array $attributes);
        
        /**
         * To delete model looking given id 
         * 
         * @param int $id
         * @return boolean
         */
        public function delete($id);

        /**
         * To update model
         * 
         * @param int $id
         * @param array $attributes
         * @return bool|int
         */
        public function update($id, array $attributes);

        /**
         * To find model 
         * 
         * @param int $id
         * @return \Illuminate\Database\Eloquent\Model|null
         */
        public function find($id);
}
