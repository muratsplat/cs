<?php 

namespace App\Libs\ClearSettle\Resource;

use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;
use App\Contracts\Repository\JSONWebToken;
use Illuminate\Contracts\Config\Repository      as Config;
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
     * @var \App\Contracts\Repository\JSONWebToken
     */
    protected $jwtRepo;
    
    /**
     * Requests
     *
     * @var array
     */
    protected $requests = [
        
        'user'  => \App\Libs\ClearSettle\Resource\Request\User::class,
    ];
    
        /**
         * Create a new Api Client Manager instance.
         *
         * @param   \Illuminate\Contracts\Container\Container   $container
         * @param   \App\Contracts\Repository\JSONWebToken      $jwtRepo
         * @param   \Illuminate\Contracts\Config\Repository     $config
         */
        public function __construct(Container $container, JSONWebToken $jwtRepo, Config $config)
        {                        
            $this->container    = $container;            
       
            $this->config       = $config;
            
            $this->jwtRepo      = $jwtRepo;
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
        
        /**
         * To create new Request
         * 
         * @param string $name      request name
         * @return \App\Libs\ClearSettle\Resource\Request
         * @throws \InvalidArgumentException
         */
        public function createNewRequest($name)
        {
            if (array_key_exists($name, $this->requests)) {
                
                $class = array_get($this->requests, $name);
                
                return new $class($this->newClient(), $this->jwtRepo);
            }
            
            throw new InvalidArgumentException("Unkown Request: [$name] !");            
        }
   
}