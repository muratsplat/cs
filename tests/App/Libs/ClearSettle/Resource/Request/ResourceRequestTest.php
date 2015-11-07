<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;
use App\Libs\ClearSettle\Resource\Request\Request;

use Mockery as m;

class ResourceRequestUserTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface 
     */
    protected $container;
    
    
    public function setUp() {
        
        parent::setUp();               
                       
    }
   
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {           
        $userM   = m::mock('App\Libs\ClearSettle\User');   
        // the example of login sson response
        $responseBody = '{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXA
iOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298","status":"APPROVED"}';
        
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $responseBody),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);   
        
        $fooRquest = new FooRequest($userM, $client);
        
        $fooRquest->request('create', []);
        
        $this->assertTrue($fooRquest->isReady());
        
        $this->assertTrue($fooRquest->isJSON());
        
        $this->assertTrue($fooRquest->isApproved());       
    }
    
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testStatusCode500Declined()
    {           
        $userM   = m::mock('App\Libs\ClearSettle\User');   
        // the example of login sson response
        $responseBody = '{"message":"Bla blaa","status":"DECLINED"}';       
        
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(500, ['X-Foo' => 'Bar'], $responseBody),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);   
        
        $fooRquest = new FooRequest($userM, $client);     
            
        $fooRquest->request('create', []);
     
        $this->assertTrue($fooRquest->isReady());
        
        $this->assertFalse($fooRquest->isApproved());    
        
        $json = $fooRquest->convertResponseBodyToJSON();
        
        $this->assertTrue($fooRquest->isJSON());
        
        $this->assertEquals($json->status, 'DECLINED');        
       
    }
    
    /**
     * Error Exception Test..
     *
     * @return void
     */
    public function testErrorException()
    {           
        $userM   = m::mock('App\Libs\ClearSettle\User');   
            
           // the example of login sson response
        $responseBody = '{"message":"Bla blaa","status":"DECLINED"}';
        
        
        // Create a mock and queue two responses.
        $mock = new MockHandler([
             new Response(500, ['X-Foo' => 'Bar'], $responseBody),      
              new RequestException("Error Communicating with Server", new GuzzleRequest('GET', 'test'))
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);   
        
        $fooRquest = new FooRequest($userM, $client);     
            
        $fooRquest->request('create', []);
     
        $this->assertTrue($fooRquest->isReady());
        
        $this->assertFalse($fooRquest->isApproved());    
        
        $json = $fooRquest->convertResponseBodyToJSON();
        
        $this->assertTrue($fooRquest->isJSON());
        
        $this->assertEquals($json->status, 'DECLINED');      
        
        $this->assertTrue($fooRquest->getMessageBag()->isEmpty());
        // adding an error for throwing "RequestException"
        $fooRquest->request('create', []);
        
        $this->assertFalse($fooRquest->getMessageBag()->isEmpty());        
       
    }
    
    /**
     * Scenario:
     *     Client try to login with wrong credential, 
     *     The api retuns 401 http status code, and the response body includes 
     *     that massage:
     *      '{"code":0,"status":"DECLINED","message":"Error: Merchant User Not Exists"}'
     * 
     * Goal: to sure actions in Request Abstract Class 
     * 
     * @test
     * @return void
     */
    public function testWrongCredentialWithHttpStatusCode401()
    {           
        
        $mockedLogger = m::mock('Illuminate\Log\Writer');        
        
        $mockedLogger->shouldReceive('info')->times(1)->andReturnNull();
        
        $app = \app();
        
        $app['log'] = $mockedLogger;
        
        
        $userM   = m::mock('App\Libs\ClearSettle\User');               
           // the example of json response of a login
        $responseBody = '{"code":0,"status":"DECLINED","message":"Error: Merchant User Not Exists"}';        
        
        // Create a mock and queue two responses.
        $mock = new MockHandler([
             new Response(401, ['X-Foo' => 'Bar'], $responseBody),                 
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);   
        
        $fooRquest = new FooRequest($userM, $client);     
            
        $fooRquest->request('create', []);
     
        $this->assertTrue($fooRquest->isReady());
        
        $this->assertFalse($fooRquest->isApproved());    
        
        $json = $fooRquest->convertResponseBodyToJSON();
        
        $this->assertTrue($fooRquest->isJSON());
        
        $this->assertEquals($json->status, 'DECLINED');      
        
        $this->assertFalse($fooRquest->getMessageBag()->isEmpty());
        
        $this->assertTrue($fooRquest->getMessageBag()->has('client_error'));      
    }   
   
}


/**
 * 
 * FooRequest for ClearSettle Api..
 * 
 */
class FooRequest extends Request {    
    
    protected $requests = [
      //method              http verb   route
        'create'         =>  ['POST' => '/merchant/user/login'],
          
    ]; 
    
     
    
}
