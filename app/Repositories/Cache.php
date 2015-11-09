<?php

namespace App\Repositories;

use InvalidArgumentException;
use Illuminate\Contracts\Cache\Repository as LaravelCache;


/**
 * Simple Caching Repository for temporary data..
 * 
 * TODO: 
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
abstract class Cache 
{
    
    /** 
     * @var \Illuminate\Contracts\Cache\Repository 
     */
    protected $cache;
      
    /**
     * Namespace for keys
     * 
     * @var string
     */
    protected $namespace;
    
    /**
     * Collection Name for keys.
     *
     * @var type 
     */
    protected $collection;
    
    /**
     * unit of time is minutes
     *
     * @var int
     */
    protected $expiration = 30;
    
        /**
         * Create RepositoryInstance
         * 
         * @param \Illuminate\Contracts\Cache\Repository $cache
         */
        public function __construct(LaravelCache $cache) 
        {            
            $this->cache    = $cache;
        }   
        
        /**
         * To get prefix key to store data on cache
         * 
         * @return string
         */
        public function getPrefixKey()
        {       
            return $this->namespace + $this->collection;
        }
        
        /**
         * To set expitation
         * 
         * @param int $minutes
         */
        public function setExpiration($minutes=30)
        {   
            $value  = (integer) $minutes;
            
            if ( $value <= 0) {
                
                throw new InvalidArgumentException("Given value must be positive number!");
            }            
            
            $this->expiration = $value;            
        }
        
        /**
         * To set expitation
         * 
         * @param int $minutes
         */
        public function getExpiration()
        {            
            return $this->expiration;            
        }
        
}
