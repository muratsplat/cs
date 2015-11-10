<?php

namespace App\Libs\ClearSettle\Resource\Request;

use Exception;
use RuntimeException;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Illuminate\Support\MessageBag;
use Psr\Http\Message\ResponseInterface              as Response;
use App\Contracts\Repository\JSONWebToken           as JWTRepo;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;
//use GuzzleHttp\Exception\BadResponseException;
//use Psr\Http\Message\StreamInterface    as Stream;
use Illuminate\Contracts\Support\MessageProvider;
use App\Contracts\Auth\ClearSettleAuthenticatable as AuthUser;
use App\Exceptions\ClearSettle\JWTokenNotStoredExc;
use App\Exceptions\ClearSettle\JWTokenNotDecodedExc;

/**
 * Abstract Request
 * 
 * This class is based to sends request Clear Settle Api..
 *
 * @author Murat Ödünç <murat.asya@gmail.com>
 */
abstract class Request implements MessageProvider
{   
    
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
     * @var \App\Contracts\Repository\JSONWebToken
     */
    protected $jwtRepo;
    
    /**
     * @var \App\User 
     */
    protected $user;
      
    /**
     * Guzzle Client Options
     * http://docs.guzzlephp.org/en/latest/request-options.html
     *
     * @var array 
     */
    protected $options = [];

        /**
         * Create Instance
         * 
         * @param \GuzzleHttp\Client                $client
         * @param \App\Contracts\Repository\JSONWebToken    $jwt
         */
        public function __construct(Client $client, JWTRepo $jwt) 
        {            
            $this->client       = $client;
            
            $this->messageBag   = new MessageBag();
            
            $this->log          = \App::make('log');
            
            $this->jwtRepo      = $jwt;
        }   
        
        
        /**
         * To create new instance
         * 
         * @return static
         */       
        public static function create(Client $client, JWTRepo $jwt)
        {
            return new static($client, $jwt);        
        }
        
        /**
         * To create new instance
         * 
         * @return static
         */
        public function newRequest()
        {
            return new static($this->client, $this->jwtRepo);        
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
         * @return self 
         */
        public function request($method) 
        {                        
            list($httpVerb, $route) = $this->getRequestParams($method);
            
            $options    = $this->getClientOptions();
                                   
            try {
                
                $response   = $this->client->request($httpVerb, $route, $options);
                
            } catch (ServerException $exc) {
                
                /**
                * When it ties to login with invalid credentials,
                * Clear Settle Api returns unexpected http status code.
                 * 
                * User login is not success, Response status code returns 500.
                * 
                * In normal scenario, the status code should be like 401.
                * 
                * Look at : https://en.wikipedia.org/wiki/List_of_HTTP_status_codes#4xx_Client_Error
                * 
                * TODO:
                * The api should harmonize W3W standarts.  
                * If this point of the api is fixed, the catch block can  be deleted..
                */
                                                             
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
         * To get params by given action for new http request 
         * 
         * @param string    $name
         * @return array    [http_verb, route]
         * @throws \InvalidArgumentException
         */
        protected function getRequestParams($name)
        {
            $params = array_get($this->requests, $name, null);
            
            if ( is_null($params) || count($params) !== 1 ) {
                
                throw new InvalidArgumentException('Given request is not found or not valid, check the request list of the object!');          
            }
            
            $httpVerb = head($params);
            
            return [$httpVerb, array_get($params, $httpVerb, null)];
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
                                            'file'  => $e->getFile(),
                                            'line'  => $e->getLine(),
                                            
                                        ]
                    );
                
                $this->messageBag->add('unknown_error', 'Service transfer is failed !');
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
        
        /**
         * To set user model
         * 
         * @param \App\User  $user
         */
        public function setUser(AuthUser $user)
        {
            if (! $user->getAuthIsExist() ) {
                
                throw new InvalidArgumentException("Given user model is not saved. Firstly try to store the model on db !");
            }
            
            $this->user = $user;
            
        }
        
        /**
         * To get user model
         * 
         * @return \App\Contracts\Auth\ClearSettleAuthenticatable
         */
        public function getUser()
        {
            return $this->user;
        }
        
        /**
         * To update JWT token for user
         * 
         * @param bool
         * @throws \App\Exceptions\ClearSettle\JWTokenNotDecodedExc
         * @throws \App\Exceptions\ClearSettle\JWTokenNotStoredExc 
         */
        public function storeNewJWTokenOnUser()
        {
            $token = $this->getJWTokenInResponse();            
            
            switch (true) {
                
                case is_null($token):
                    
                    throw new JWTokenNotDecodedExc('JWT is not saved! JSON Web Token is not parsed in the json response. '
                            . 'Clear Settle Api maybe has been returned unknown reponse body. ');
                
                case ! $this->userReady():
                    
                    throw new JWTokenNotStoredExc('JWT is not saved! Firstly user should had stored. ');
                
                default :
                    
                    $this->jwtRepo->storeByUser($this->user, $token);
                    
                    return true;
            }            
            
        }
        
        /**
         * Determine if the user is setted and stored on db
         * for api requests
         * 
         * @return bool
         */
        public function userReady()
        {            
            return ! is_null($this->user) && $this->user->exists;           
        }
        
        /**
         * To get JWT token in json reponse if response body is getted..
         * 
         * @return string|null
         */
        protected function getJWTokenInResponse()
        {            
            //            '{
            //                "token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9s
            //                ZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXA
            //                iOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298",
            //                
            //                "status":"APPROVED"
            //            }'
            $stdObject = $this->convertResponseBodyToJSON();
            
            return isset($stdObject->token) ? $stdObject->token : null;        
        }
        
        /**
         * Determine if user has a jtw token
         * 
         * @return bool
         */
        public function userHasJWT()
        {
            if ( ! $this->userReady() ) { return false; }
            
            $user = $this->getUser();
            
            return $this->jwtRepo->isStoredByUser($user);          
        }
        
        /**
         * To get JWT token if it is exist
         * 
         * @return string|null 
         */
        public function getUserJWT()
        {
            if ($this->userReady()) { 
                
                $user = $this->getUser(); 
                
                return $this->jwtRepo->getByUser($user, 'null');                
            }       
        }        
        
        /**
         * To get client options
         * 
         * @param array $options
         * @return array
         */
        public function getClientOptions()
        {            
            if ( $this->userHasJWT() ) {
                
               $headers = ['Authorization' => $this->getUserJWT()];
               
               $this->putHeaders($headers);                
            }                   
            return $this->options;
        }
        
        
        /**
         * Add new options for http clients
         * 
         * @param string $key
         * @param mixed|Closure $value
         */
        public function putOptions($key, $value) 
        {
            $this->options[(string)$key] = value($value);            
        }
        
        /**
         * To put form parameter to options
         * 
         * @param array $parameters
         * @return void
         */
        public function putParams(array $parameters)
        {
            $this->putOptions('form_params', $parameters);
        }
        
        /**
         * Add header to each request
         * 
         * @param array $headers
         * @return void
         */
        public function putHeaders(array $headers)
        {
            $this->putOptions('headers', $headers);
        }
                            
}
