<?php

namespace App\Libs\ClearSettle;

use Exception;
use ArrayAccess;
use Illuminate\Contracts\Auth\Authenticatable;

//use JsonSerializable;
//use Illuminate\Contracts\Support\Jsonable;
//use Illuminate\Contracts\Support\Arrayable;

/**
 * Description of User
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class User implements ArrayAccess, Authenticatable 
{   
    
    /**
     * User Attributes
     *  - ID
     *  - email
     *  - token
     *  - name
     *  - parentId
     *  - ...
     *  - ...
     * 
     * @var array
     */
    protected $attributes = [];
    
    /**
     * The session used by the guard.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;
    
    
        /**
         * Create User Instance
         * 
         * @param array $attributes
         */
        public function __construct(array $attributes=[]) 
        {
            $this->attributes   = $attributes;
            
            $this->session      = \app('session'); 
        }
        
        /**
         * Get the unique identifier for the user.
         *
         * @return int|null
         */
        public function getAuthIdentifier() 
        {            
            return array_get($this->attributes, 'id', null);
        }

        /**
         * Get the password for the user.
         *
         * @return string
         */
        public function getAuthPassword() 
        {
            throw new Exception('This method is not implemented !');
        }
        
         /**
         * Get the email for the user.
         *
         * @return string
         */
        public function getAuthEmail() 
        {
            $email =  array_get($this->attributes, 'email', null);
            
            if ( is_null($email) ) {
                
                throw new Exception("Email value is needed !");
            }
            
            return $email;
            
        }        
        
        /**
         * To create new instance
         * 
         * @param array $attributes
         * @return self
         */
        public static function create(array $attributes=[]) 
        {
            return new static($attributes);        
        }

        /**
         * Get the token value for the "remember me" session.
         *
         * @return string
         */
        public function getRememberToken() {
            
             throw new Exception('This method is not implemented !');
        }

        /**
         * Set the token value for the "remember me" session.
         *
         * @param  string  $value
         * @return void
         */
        public function setRememberToken($value) 
        {            
             throw new Exception('This method is not implemented !');
        }

        /**
         * Get the column name for the "remember me" token.
         *
         * @return string
         */
        public function getRememberTokenName() 
        {
             throw new Exception('This method is not implemented !');
        }
        
        /**
	 * Whether a offset exists
         * 
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
        {
            return isset($this->attributes[$offset]);
        }

	/**
	 * Offset to retrieve
         * 
	 * @param mixed $offset
	 * @return mixed 
	 */
        public function offsetGet($offset) 
        {
            return array_get($this->attributes, $offset, null);
        }

	/**
	 * Offset to set
         * 
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset     The offset to assign the value to.
	 * @param mixed $value      The value to set.
         * @return void
	 */
	public function offsetSet($offset, $value) 
        {
            $this->attributes[$offset] = $value;
        }

	/**
	 * Offset to unset
	 * @param mixed $offset The offset to unset.
	 * @return void
	 */
	public function offsetUnset($offset)
        {
            unset($this->attributes[$offset]);            
        }
        
        /**
         * To set attribute by key name
         * 
         * @param string $name
         * @param mixed $value
         */
        public function __set($name, $value)
        {
            $this->offsetSet($name, $value);           
        }
        
        /**
         * To get attribute by key name
         * 
         * @param string $name
         * @return mixes
         */
        public function __get($name) 
        {
            return $this->offsetGet($name);            
        }
        
        /**
         * Determine if the key is exist
         * 
         * @param string $name
         * @return bool
         */
        public function __isset($name) 
        {
            return $this->offsetExists($name);        
        }       
        
        /**
         * Set the jwt token value 
         * 
         * @param  string  $value
         * @return void
         */
        public function setJWTToken($value) 
        {   
            $this->attributes['jwt'] = $value;            
        }
        
        /** 
         * To get the jwt token value 
         * 
         * @return string|null
         */
        public function getJWTToken() 
        {   
            return $this->attributes['jwt'];            
        }
        
        
        public function saveOnSession()
        {
            
        }

}
