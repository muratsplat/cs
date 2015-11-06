<?php

namespace App\Libs\ClearSettle\Resource\Request;

use Exception;
use GuzzleHttp\Client;
use App\Libs\ClearSettle\User;
use Illuminate\Support\MessageBag;
use Psr\Http\Message\ResponseInterface  as Response;
use Illuminate\Contracts\Support\MessageProvider;

/**
 * Abstract Request
 * 
 * This class  is based to sends request Clear Settle Api..
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
abstract class Request implements MessageProvider
{   
    
    /**
     * User Attributes
     * @var \App\Libs\ClearSettle\User
     */
    protected $user;
    
    /**
     * Guzzle Http Pre-Configured Client
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;
    
    /**
     * Message Bag
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $messageBag;
    
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;
    

        /**
         * Create Instance
         * 
         * @param \App\Libs\ClearSettle\User    $user
         * @param G\uzzleHttp\Client            $client
         */
        public function __construct(User $user, Client $client) 
        {
            $this->user         = $user;
            
            $this->client       = $client;
            
            $this->messageBag   = new MessageBag();
        }
        
        
        /**
         * To get Message Bag
         * 
         * @return Illuminate\Contracts\Support\MessageProvider
         */
        public function getMessageBag() 
        {            
            return $this->getMessageBag();            
        }   
        
        
        /**
         * To send http request to Clear Settle Api
         * 
         * @param string $method    PUT, GET, PATCH, POST, DELETE
         * @param strig $url        /merchent/user
         * @param array $options
         * @return \Psr\Http\Message\ResponseInterface
         */
        public function request($method, $url, array $options) 
        {            
            return $this->client->request($method, $url, $options);
        }        
        
        /**
         * To set Response
         * 
         * @param \Psr\Http\Message\ResponseInterface  $res
         */
        protected function setResponse(Response $res) 
        {
            $this->response = $res;
        }
        
        /**
         * Determine if the reguest is success
         * 
         * @return bool
         */
        public function isSuccess()
        {            
            return $this->response->getStatusCode() === 200;
        }        
        
        /**
         * Determine if Response object is setted..
         * 
         * @return bool
         */
        public function isReady()
        {            
            return ! $this->response;
        }
             
}
