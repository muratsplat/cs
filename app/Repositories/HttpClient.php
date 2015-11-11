<?php

namespace App\Repositories;

use InvalidArgumentException;
use Illuminate\Contracts\Cache\Repository as LaravelCache;


/**
 * Simple Repository for CRUD jobs in Clear Settle API
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
abstract class HttpClient 
{
    
    /** 
     * @var \Illuminate\Contracts\Cache\Repository 
     */
    protected $cache;
    
    /**
     * Collection Name for keys.
     *
     * @var type 
     */
    protected $collection;    
    
        /**
         * Create RepositoryInstance
         * 
         * @param \Illuminate\Contracts\Cache\Repository $cache
         */
        public function __construct(LaravelCache $cache) 
        {            
            $this->cache    = $cache;
        }   
        
        
}
