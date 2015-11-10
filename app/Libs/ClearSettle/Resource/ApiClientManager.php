<?php 

namespace App\Libs\ClearSettle\Resource;

use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Illuminate\Contracts\Container\Container;

/**
 * This class manages ClearSettle API client
 * 
 * This class will create a pre-configured http client by looking app config files. 
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
class ApiClientManager {
        
    /**
     * @var \Illuminate\Contracts\Container\Container 
     */
    protected $container;
    
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;
    
        /**
         * Create a new Api Client Manager instance.
         *
         * @param  \Illuminate\Contracts\Container\Container  $container
         */
        public function __construct(Container $container)
        {                        
            $this->container    = $container;            
       
            $this->config       = $container->make('config');            
        }        
   
        /**
         * To get Laravel Config Instance
         * 
         * @return \Illuminate\Contracts\Config\Repository
         */
        protected function getConfig()
        {
            return $this->config;
        }
               
        /**
         * To create new Http Client
         * 
         * @return \GuzzleHttp\Client
         */
        public function newClient()
        {
            $name       = $this->getApiName();
                
            $options    = $this->getOptions($name);
           
            return new Client($options);
        }
        
        /**
         * To get api name from Config Instance 
         * 
         * @param string|null
         * @throws \Exception
         */
        private function getApiName()
        {
            $name = $this->getConfig()->get('api.default', null);    
            
            if ( is_null($name) ) {
                
                throw new Exception('An Api must be specified !');
            }
            
            return $name;            
        }
        
        
        /**
         * To get api options by the name
         * 
         * @param string    api name
         * @return array
         * @throws \InvalidArgumentException
         */
        private function getOptions($apiName)
        {
            $options = $this->getConfig()->get("api.apis.$apiName", []);
            
            if ( empty($options) ) {
                
                throw new InvalidArgumentException("Api options are not defined");                
            }
            
            return $options;             
        }
   
}