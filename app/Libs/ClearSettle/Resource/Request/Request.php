<?php

namespace App\Libs\ClearSettle\Resource\Request;

use Exception;
use RuntimeException;
use GuzzleHttp\Client;
use InvalidArgumentException;
use App\Libs\ClearSettle\User;
use Illuminate\Support\MessageBag;
use Psr\Http\Message\ResponseInterface  as Response;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Exception\BadResponseException;


//use Psr\Http\Message\StreamInterface    as Stream;
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
     * Async Requests
     *
     * @var type 
     */
    protected $async = false;
    
    /**
     * List of request group
     *
     * @var type 
     */
    protected $requests = [];    
    
    /**
     * Clear Settle Api Status 
     */
    const DECLINED = "DECLINED";
    const APPROVED = "APPROVED";       
        
    /**
     * Laravel Log Service
     *
     * @var \Illuminate\Contracts\Logging\Log
     */
    protected $log;

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
            
            $this->log          = \App::make('log');
        }
        
        
        /**
         * To get Message Bag
         * 
         * @return Illuminate\Contracts\Support\MessageProvider
         */
        public function getMessageBag() 
        {            
            return $this->messageBag;            
        }   
        
        
        /**
         * To send http request to Clear Settle Api
         * 
         * @param string $method    method name 
         * @param strig $url        /merchent/user
         * @param array $options
         * @return self 
         */
        public function request($method, array $options) 
        {            
            $params     = $this->getRequestParams($method);
            
            $httpVerb   = key($params);
            
            $url        = array_get($params, $httpVerb);
                       
            /**
             * Clear Settle Api http status code is unexpected.
             * User login is not success, Response status code returns 500.
             * 
             * In normal scenario, the status code should be like 401.
             * 
             * Look at : https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#4xx_Client_Error
             * 
             * TODO:
             * The api should harmonize W3W standarts.  
             * If this point of the api is fixed, all try block can  be deleted..
             */
            try {
                
                $response   = $this->client->request($httpVerb, $url, $options);
                
            } catch (ServerException $exc) {
                                                             
                $response = $exc->getResponse();
                
            } catch (Exception $e) {
                
                $this->catchAndReport($e);
                        
                $response = $e->getResponse();
                             
            } 
            
            if (! is_null($response) ) {
                
                 $this->setResponse($response);           
            }          
            
            return $this;
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
         * Determine if the reguest is success.
         *
         * This methods only check http status code in the reponse header!
         * 
         * @return bool
         */
        public function isSuccess()
        {            
            return $this->isReady() && $this->response->getStatusCode() === 200;                  
        }        
        
        /**
         * Determine if Response object is setted..
         * 
         * @return bool
         */
        public function isReady()
        {            
            return is_object($this->response);
        }
        
        /**
         * Determine if response body is JSON Object
         * 
         * @return bool
         */
        public function isJSON()
        {
            if ( ! $this->isReady() ) { return false; }
            
            $body = $this->getBodyOnResponse();
                       
            $jSon = json_decode($body);   
                
            return is_object($jSon);
        }
        
        /**
         * To get Body Message in The Response
         * 
         * @return \Psr\Http\Message\StreamInterface
         * @throws \RuntimeException
         */
        protected function getBodyOnResponse()
        {
            if ( $this->isReady() && $this->response->getBody())  {
                
                   return $this->response->getBody();
            }
            
            throw  new RuntimeException('Body Message is broken ! The stream may be not ended..');
        }
        
        /**
         * To convert json response to stdObject
         * 
         * @return stdClass|null
         */
        public function convertResponseBodyToJSON()
        {
            return $this->isReady() && $this->isJSON() 
                    
                        ? json_decode($this->getBodyOnResponse()) 
                        : null;
        }
        
        /**
         * To get params by given action to request 
         * 
         * @param type $name
         * @return array    [http_verb, route]
         * @throws \InvalidArgumentException
         */
        public function getRequestParams($name)
        {
            $params = array_get($this->requests, $name, null);
            
            if ( is_null($params) || count($params) !== 1 ) {
                
                throw new InvalidArgumentException('Given request is not found or not valid, check the request list of the object!');          
            }
            
            return $params;
        }
        
        
        /**
         * Determine if the api approved to senden request
         * 
         * @return bool
         */
        public function isApproved()                
        {
            if ( $this->isReady() && $this->isJSON() ) {
                
                $message = $this->convertResponseBodyToJSON();
                               
                return $message->status === self::APPROVED;
            }
            
            return false;
            
        }           
        
        /**
         * Catch throws and report them via log service
         * 
         * @param Exception $exc
         * @throws type
         */
        protected function catchAndReport(Exception $exc) 
        {
            try {
                
                throw $exc;
                
                /**
                 * Guzlle Http Exceptions: http://docs.guzzlephp.org/en/latest/quickstart.html#exceptions
                 */
    
            } catch (ClientException $e) {
                
                $this->sendMessageToLogService($e, 'info');
                
                $this->messageBag->add('client_error', 'Client request is invalid!');
                
            } catch (ConnectException $e) {
                
                $this->sendMessageToLogService($e, 'error');
                
                $this->messageBag->add('connection_error', 'The service is inaccessible.');
                
            } catch (ServerException $e) {
                
                $this->sendMessageToLogService($e, 'error');
                
                $this->messageBag->add('service_error', 'The service is failed !');
                
            }  catch (RequestException $e) {
                
                $this->sendMessageToLogService($e, 'error');
                
                $this->messageBag->add('request_error', 'Your request is failed!');
            } catch (TransferException $e) {
                
                $this->sendMessageToLogService($e, 'error');
                
                $this->messageBag->add('general_error', 'Service transfer is failed !');
            }            
             catch (Exception $e) {
                
                if (!\App()->runningUnitTests() ) {
                    
                    throw $e;
                }
                
                $this->log->critical('Unknown Error: ', 
                                        [
                                            'msg'   => $e->getMessage(), 
                                            'file'  => $e->getLine(),
                                            
                                        ]
                    );
                
                $this->messageBag->add('general_error', 'Service transfer is failed !');
            }      
                    
        }     
        
        
        /**
         * To send throw as Error message to Laravel log service
         * 
         * @param \GuzzleHttp\Exception\RequestException $ex
         */
        protected function sendMessageToLogService(RequestException $ex, $type='error')
        {             
            $message = [   
                'request_url'           => $ex->getRequest()->getUri()->getPath(),
                'request_query'         => $ex->getRequest()->getUri()->getQuery(),
                'response_headers'      => $ex->getResponse(),
                'response_status_code'  => null,
                'msg'                   => $ex->getMessage(), 
                'file'                  => $ex->getFile(),
                'line'                  => $ex->getLine(),
            ];
            
            if ($ex->hasResponse()) {
                
                $message['response_status_code'] = $ex->getResponse()->getStatusCode();
            }                
                
            $this->log->{$type}('The request is unsuccess !', $message);
        }
             
}
