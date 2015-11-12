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
use App\Libs\ClearSettle\Resource\Request\Transaction;


use Mockery as m;

class ResourceRequestTransactionTest extends TestCase
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
     * test
     *
     * @return void
     */
    public function testSendsReportRequest()
    {  
        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');    
        $jwtRepo->shouldReceive('isStoredByUser')->andReturn(true)->times(1);
        
        $jwtRepo->shouldReceive('getByUser')->andReturn($this->token)->times(1);
        
        
        $successInfo = '{
	"status" : "APPROVED",
	"response" : [
		{
		"count" :283,
		"total" :28300,
		"currency": "USD"
		},
		{
		"count" :987,
		"total" :282300,
		"currency": "AFN"
		}
            ]
        }';
        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $successInfo),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $request = new Transaction($client, $jwtRepo);       
        
                
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->shouldReceive('getAuthIsExist')->andReturn(true);
        $userModel->shouldReceive('getAuthCSMerchantUserId')->andReturn(1);
        $userModel->shouldReceive('getAuthCSMerchantId')->andReturn(1);
        $userModel->exists = true;        
        
        $jsonReponse = $request->report($userModel, '1990-01-01', '2015-01-01', null, null);
        
        $this->assertTrue($jsonReponse);
               
        $this->assertFalse($request->hasError());
        
        $this->assertEquals(json_decode($successInfo), $request->getBodyAsObject());
                
    }  
    
    
    /**
     * This test methods sends real http request remote server !!!!
     */
    public function disable_testReportWithoutMockedObjects() 
    {
        $client = \app('app.clearsettle.clients')->newClient();
        
        $jwtRepo= $this->getjwtRepoInApp();
        
        $loginRequest = new User($client, $jwtRepo);
       
        $credentials = [
            'email'     => 'demo@bumin.com.tr',
            'password'  => 'cjaiU8CV',
        ];
        
           
        $loginRequest->putOptions('form_params', $credentials);        
        
        $userRepo  = $this->getUserRepo();
        
        $this->callMigration();
        
        $newUser = $userRepo->findOrCreateByEmail($credentials['email']);
        
                
        $this->assertTrue($loginRequest->login($newUser, $credentials));   
        
        // mocking user model
        $userModel  = $loginRequest->getUser();                        
        
        $request = new Transaction($client, $jwtRepo);
        $jsonReponse = $request->report($userModel, '1990-01-01', '2015-11-01', null, null);
      
        
        $this->assertNotNull($request->getUser());
        
        //$this->assertArrayHasKey('headers', $userRequest->getClientOptions());
        $this->assertTrue($jsonReponse);        
        $this->assertTrue($request->isSuccess());       
        
        
    }
    
    /**
     * 
     * @return \App\Contracts\Repository\JSONWebToken
     */
    private function getjwtRepoInApp()
    {
        return \app('App\Contracts\Repository\JSONWebToken');
    }
    
    /**
     * 
     * @return \App\Contracts\Repository\User
     */
    private function getUserRepo()
    {
        return \app('App\Contracts\Repository\User');
    }
    
    
}
