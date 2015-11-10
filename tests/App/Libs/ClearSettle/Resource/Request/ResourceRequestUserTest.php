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
    
    /**
     * @var string
     */
    protected $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXA
iOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298";
    
    /**
     * @var string
     */
    protected $successResponseBody = '{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXA
iOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298","status":"APPROVED"}';
    
    /**
     * @var string
     */
    protected $unsuccessResponseBody = '{"code":0,"status":"DECLINED","message":"Error: Merchant User Not Exists"}';
    
    
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
    public function testSimpleFirst()
    {       
        
        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');           
        $jwtRepo->shouldReceive('storeByUser')->times(1)->andReturnNull();
        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $this->successResponseBody),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($client, $jwtRepo);       
        
        $credentials = ['email' => 'foo@bar.com', 'password' => 'secret'];
  
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->exists = true;        
        $userModel->id = 1;
        
        $jsonReponse = $userRequest->login($userModel, $credentials);
        
        $this->assertTrue($jsonReponse);
        
        $this->assertTrue($userRequest->isReady());
        $this->assertTrue($userRequest->isApproved());
        
        $this->assertTrue($userRequest->isJSON());        
        $this->assertTrue($userRequest->isSuccess());             
    }   
    
      
    /**
     * A basic unit test
     *
     * @return void
     */
    public function testClientOptions()
    {       

        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');           
        $jwtRepo->shouldReceive('storeByUser')->times(1)->andReturnNull();          
        $jwtRepo->shouldReceive('isStoredByUser')->times(1)->andReturn(true);
        $jwtRepo->shouldReceive('getByUser')->times(2)->andReturn($this->token);
        
        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $this->successResponseBody),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($client, $jwtRepo);       
        
        $credentials = ['email' => 'foo@bar.com', 'password' => 'secret'];
  
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->exists = true;        
        $userModel->id = 1;
        
        $jsonReponse = $userRequest->login($userModel, $credentials);
        
        $this->assertTrue($jsonReponse);
        
        $this->assertEquals($this->token, $userRequest->getUserJWT());
        
        $shouldbeOptions = ['form_params' => $credentials, 'headers' => ['Authorization' => $this->token]];
        
        $this->assertEquals($shouldbeOptions, $userRequest->getClientOptions());
    }
    
    /**
     * Wrong Credentials..
     *
     * @return void
     */
    public function testWrongCredentials()
    {           
        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');           
        $jwtRepo->shouldReceive('storeByUser')->times(0)->andReturnNull();          
        $jwtRepo->shouldReceive('isStoredByUser')->times(0)->andReturn(false);
        $jwtRepo->shouldReceive('getByUser')->times(0)->andReturn($this->token);
                
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(401, ['X-Foo' => 'Bar'], $this->unsuccessResponseBody),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->exists = true;        
        $userModel->id = 1;
        
        $userRequest = new User($client, $jwtRepo);       
        
        $credentials = ['email' => 'foo@bar.com', 'password' => 'secret'];
        
        $result = $userRequest->login($userModel, $credentials);
        
        $this->assertFalse($result);
        
        $this->assertTrue($userRequest->isReady());
        $this->assertFalse($userRequest->isApproved());
        
        $this->assertTrue($userRequest->isJSON());        
        $this->assertFalse($userRequest->isSuccess());     
    }   
}
