<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
//use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Handler\MockHandler;
//use GuzzleHttp\Exception\RequestException;
//use App\Libs\ClearSettle\Resource\Request\Request;


use App\Libs\ClearSettle\Resource\Request\User;


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
    
    public function tearDown() {
        parent::tearDown();
        
        m::close();
    }
   
    /**
     * A basic unit test
     *
     * @return void
     */
    public function testBasicExample()
    {           
        $userM   = m::mock('App\Libs\ClearSettle\User');
        
        $userM->shouldReceive('getAuthEmail')->andReturn('foo@bar.com');
        
        $userM->shouldReceive('getAuthPassword')->andReturn('secret');
        
        $responseBody = '{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXA
iOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298","status":"APPROVED"}';
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $responseBody),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($userM, $client);       
        
        $params  = $userRequest->getRequestParams('login');
        
        $this->assertEquals(['POST' => '/merchant/user/login'], $params);
        
        $jsonReponse = $userRequest->login();
        
        $this->assertNotNull($jsonReponse);
        
        $this->assertTrue($userRequest->isReady());
        $this->assertTrue($userRequest->isApproved());
        
        $this->assertTrue($userRequest->isJSON());        
        $this->assertTrue($userRequest->isSuccess());     
    }
    
    /**
     * Wrong Credentials..
     *
     * @return void
     */
    public function testWrongCredentials()
    {           
        $userM   = m::mock('App\Libs\ClearSettle\User');
        
        $userM->shouldReceive('getAuthEmail')->andReturn('foo@bar.com');
        
        $userM->shouldReceive('getAuthPassword')->andReturn('secret');
        
        $responseBody = '{"code":0,"status":"DECLINED","message":"Error: Merchant User Not Exists"}';
        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(401, ['X-Foo' => 'Bar'], $responseBody),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($userM, $client);       
        
        $params  = $userRequest->getRequestParams('login');
        
        $this->assertEquals(['POST' => '/merchant/user/login'], $params);
        
        $jsonReponse = $userRequest->login();
        
        $this->assertNotNull($jsonReponse);
        
        $this->assertTrue($userRequest->isReady());
        $this->assertFalse($userRequest->isApproved());
        
        $this->assertTrue($userRequest->isJSON());        
        $this->assertFalse($userRequest->isSuccess());     
    } 
   
}
