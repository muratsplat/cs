<?php 

namespace App\Libs\Bumin\Resource;

use \Illuminate\Contracts\Container\Container;

/**
 * This class creates resources to connect Bumin API
 * 
 * Resources Class will generate a connection to Bumin API. The connection  will 
 * supports listing, creating, deleting, updating actions on Bumin API
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class ConnectionFactory {
    
    
    /**
     * @var \Illuminate\Contracts\Container\Container 
     */
    protected $container;
    
        /**
         * Create a new Resource Factory instance.
         *
         * @param  \Illuminate\Contracts\Container\Container  $container
         * @return void
         */
        public function __construct(Container $container)
        {            
            
            $this->container    = $container;
            
            $resource           = $container->make('App\Contracts\Weather\Repository\IForecastResource');
            
            $this->log          = $container->make('log');
            
            $this->setForeCastResource($resource);
        }    
   
}