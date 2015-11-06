<?php

namespace App\Libs\ClearSettle\Resource\Request;

use Exception;
use GuzzleHttp\Client;
use App\Libs\ClearSettle\User;
use Illuminate\Support\MessageBag;
use Illuminate\Contracts\Support\MessageProvider;

/**
 * Abstract Request
 * 
 * This class  is based to sends request Clear Settle Api..
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
Abstract Class Request implements MessageProvider
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
      
}
