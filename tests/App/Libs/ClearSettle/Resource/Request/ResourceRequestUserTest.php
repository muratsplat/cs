<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
    protected $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXAiOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298";
    
    /**
     * @var string
     */
    protected $successResponseBody = '{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXAiOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298","status":"APPROVED"}';
    
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
        
        $userModel->shouldReceive('getAuthIsExist')->andReturn('true');
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
        $userModel->shouldReceive('getAuthIsExist')->andReturn('true');
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
    
     /**
     * Info test
     *
     * @return void
     */
    public function testSendsInfoRequest()
    {       

        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');    
        $jwtRepo->shouldReceive('isStoredByUser')->andReturn(true)->times(2);
        
        $jwtRepo->shouldReceive('getByUser')->andReturn($this->token)->times(3);
        
        
        $successInfo = '{
                "name":"Merchant",
                "role":"admin",
                "email":"merchant@text.com",
                "merchantId":1,
                "status":"APPROVED"
               }';
        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $successInfo),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($client, $jwtRepo);       
        
        $params = ['merchantUserId' => 1];
  
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->shouldReceive('getAuthIsExist')->andReturn(true);
        $userModel->shouldReceive('getAuthCSMerchantUserId')->andReturn(1);
        $userModel->exists = true;        
        $userModel->id = 1;
        
        $jsonReponse = $userRequest->info($userModel);
        
        $this->assertTrue($jsonReponse);
        
        $this->assertEquals($this->token, $userRequest->getUserJWT());
        
        $shouldbeOptions = ['form_params' => $params, 'headers' => ['Authorization' => $this->token]];
        
        $this->assertEquals($shouldbeOptions, $userRequest->getClientOptions());
        
        $this->assertFalse($userRequest->hasError());
        
        $this->assertEquals(json_decode($successInfo), $userRequest->getBodyAsObject());
    }
    
    /**
     * Info test
     *
     * @return void
     */
    public function testSendsInfoRequestFailedStatusDeclined()
    {       

        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');    
        $jwtRepo->shouldReceive('isStoredByUser')->andReturn(true)->times(2);
        
        $jwtRepo->shouldReceive('getByUser')->andReturn($this->token)->times(3);
        
        
        $declinedInfo = '{
                "name":"Merchant",
                "status":"DECLINED",
                "code": 0,
                "message":"bla bla",
               }';
        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(404, ['X-Foo' => 'Bar'], $declinedInfo),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($client, $jwtRepo);       
        
        $params = ['merchantUserId' => 1];
  
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->shouldReceive('getAuthIsExist')->andReturn(true);
        $userModel->shouldReceive('getAuthCSMerchantUserId')->andReturn(1);
        $userModel->exists = true;        
        $userModel->id = 1;
        
        $jsonReponse = $userRequest->info($userModel);
        
        $this->assertFalse($jsonReponse);
        
        $this->assertEquals($this->token, $userRequest->getUserJWT());
        
        $shouldbeOptions = ['form_params' => $params, 'headers' => ['Authorization' => $this->token]];
        
        $this->assertEquals($shouldbeOptions, $userRequest->getClientOptions());
        
        $this->assertTrue($userRequest->hasError());        
             
        $this->assertEquals(json_decode($declinedInfo), $userRequest->getBodyAsObject());
        
        $this->assertNotNull($userRequest->getUser());
    }
    
    
    /**
     * This test methods sends real http request remote server !!!!
     */
    public function disable_testWithoutMockedObjects() 
    {
        $client = \app('app.clearsettle.clients')->newClient();
        
        $jwtRepo= $this->getjwtRepoInApp();
        
        $userRequest = new User($client, $jwtRepo);
       
        $credentials = [
            'email'     => 'demo@bumin.com.tr',
            'password'  => 'cjaiU8CV',
        ];
        
           
        $userRequest->putOptions('form_params', $credentials);        
        
        $userRepo  = $this->getUserRepo();
        
        $this->callMigration();
        
        $newUser = $userRepo->findOrCreateByEmail($credentials['email']);
        
        $this->assertTrue($userRequest->login($newUser, $credentials));   
        
                // mocking user model
        $userModel  = $userRequest->getUser();
        
        $this->assertNotNull($userModel);
        
        $params = ['merchantUserId' => 1];
        
         
        $jsonReponse = $userRequest->info($userModel);
        
        $this->assertNotNull($userRequest->getUser());
        
        $this->assertArrayHasKey('headers', $userRequest->getClientOptions());
        $this->assertTrue($jsonReponse);        
        $this->assertTrue($userRequest->isSuccess());       
        
    }
    
    
    
    /**
     * This test methods sends real http request remote server !!!!
     */
    public function disable_testWithoutMockedObjectsNotFoundUser() 
    {
        $client = \app('app.clearsettle.clients')->newClient();
        
        $jwtRepo= $this->getjwtRepoInApp();
        
        $userRequest = new User($client, $jwtRepo);
       
        $credentials = [
            'email'     => 'demo@bumin.com.tr',
            'password'  => 'cjaiU8CV',
        ];
        
           
        $userRequest->putOptions('form_params', $credentials);        
        
        $userRepo  = $this->getUserRepo();
        
        $this->callMigration();
        
        $newUser = $userRepo->findOrCreateByEmail($credentials['email']);
        
        $this->assertTrue($userRequest->login($newUser, $credentials));   
        
                // mocking user model
        $userModel  = $userRequest->getUser();
        
        $this->assertNotNull($userModel);
                
        $jsonReponse = $userRequest->info($userModel,99);
        //var_dump($userRequest->getClientOptions());
        //var_dump($userRequest->getBodyAsObject());
        $this->assertNotNull($userRequest->getUser());
        
        $this->assertArrayHasKey('headers', $userRequest->getClientOptions());
        $this->assertTrue($jsonReponse);        
        $this->assertTrue($userRequest->isSuccess());        
    }
    
     /**
     * test
     *
     * @return void
     */
    public function testSendsCreateRequest()
    {  
        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');    
        $jwtRepo->shouldReceive('isStoredByUser')->andReturn(true)->times(2);
        
        $jwtRepo->shouldReceive('getByUser')->andReturn($this->token)->times(3);
        
        
        $successInfo = '{
            "status":"APPROVED",
            "message":"Merchant User Created",
            "id":59
            }';
        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $successInfo),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($client, $jwtRepo);       
        
        $params = [
            'subMerchantId' => null,
            'name'          => 'Foo',
            'email'         => 'foo@bar.com',
            'password'      => 'secret',
            ];
          
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->shouldReceive('getAuthIsExist')->andReturn(true);
        $userModel->shouldReceive('getAuthCSMerchantUserId')->andReturn(1);
        $userModel->shouldReceive('getAuthCSMerchantId')->andReturn(1);
        $userModel->exists = true;        
        
        $jsonReponse = $userRequest->create($userModel, $params);
        
        $this->assertTrue($jsonReponse);
        
        $this->assertEquals($this->token, $userRequest->getUserJWT());
        
        $params['merchantId'] = 1;
        $shouldbeOptions = ['form_params' => $params, 'headers' => ['Authorization' => $this->token]];
        
        $this->assertEquals($shouldbeOptions, $userRequest->getClientOptions());
        
        $this->assertFalse($userRequest->hasError());
        
        $this->assertEquals(json_decode($successInfo), $userRequest->getBodyAsObject());
    }
    
    /**
     * This test methods sends real http request remote server !!!!
     */
    public function disable_testCreateWithoutMockedObjects() 
    {
        $client = \app('app.clearsettle.clients')->newClient();
        
        $jwtRepo= $this->getjwtRepoInApp();
        
        $userRequest = new User($client, $jwtRepo);
       
        $credentials = [
            'email'     => 'demo@bumin.com.tr',
            'password'  => 'cjaiU8CV',
        ];
        
           
        $userRequest->putOptions('form_params', $credentials);        
        
        $userRepo  = $this->getUserRepo();
        
        $this->callMigration();
        
        $newUser = $userRepo->findOrCreateByEmail($credentials['email']);
        
        $this->assertTrue($userRequest->login($newUser, $credentials));   
        
                // mocking user model
        $userModel  = $userRequest->getUser();
        
        $this->assertNotNull($userModel);
                        
        $params = [
            'subMerchantId' => null,
            'name'          => 'Foo' . str_random(),
            'email'         => str_random(). '@bar.com',
            'password'      => 'secret',
        ];
        
         
        $jsonReponse = $userRequest->create($userModel, $params);
        
        $this->assertNotNull($userRequest->getUser());
        
        //$this->assertArrayHasKey('headers', $userRequest->getClientOptions());
        $this->assertTrue($jsonReponse);        
        $this->assertTrue($userRequest->isSuccess());       
        
        var_dump($userRequest->getBodyAsObject());
    }
   
    /**
     * test
     *
     * @return void
     */
    public function testSendsUpdateRequest()
    {  
        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');    
        $jwtRepo->shouldReceive('isStoredByUser')->andReturn(true)->times(2);
        
        $jwtRepo->shouldReceive('getByUser')->andReturn($this->token)->times(3);
        
        
        $successInfo = '{
            "status":"APPROVED",
            "message":"Merchant User Updated"
            }';
        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $successInfo),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($client, $jwtRepo);       
        
        $params = [
            'id'            => 1,
            'name'          => 'FooUpdate',
            'email'         => 'foo-update@bar.com',
            'role'          => 'admin',
            ];
          
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->shouldReceive('getAuthIsExist')->andReturn(true);
        $userModel->shouldReceive('getAuthCSMerchantUserId')->andReturn(1);
        $userModel->shouldReceive('getAuthCSMerchantId')->andReturn(1);
        $userModel->exists = true;        
        
        $jsonReponse = $userRequest->update($userModel, $params);
        
        $this->assertTrue($jsonReponse);
        
        $this->assertEquals($this->token, $userRequest->getUserJWT());
       
        $shouldbeOptions = ['form_params' => $params, 'headers' => ['Authorization' => $this->token]];
        
        $this->assertEquals($shouldbeOptions, $userRequest->getClientOptions());
        
        $this->assertFalse($userRequest->hasError());
        
        $this->assertEquals(json_decode($successInfo), $userRequest->getBodyAsObject());
    }
    
     /**
     * This test methods sends real http request remote server !!!!
     */
    public function disable_testUpdateWithoutMockedObjects() 
    {
        $client = \app('app.clearsettle.clients')->newClient();
        
        $jwtRepo= $this->getjwtRepoInApp();
        
        $userRequest = new User($client, $jwtRepo);
       
        $credentials = [
            'email'     => 'demo@bumin.com.tr',
            'password'  => 'cjaiU8CV',
        ];
        
           
        $userRequest->putOptions('form_params', $credentials);        
        
        $userRepo  = $this->getUserRepo();
        
        $this->callMigration();
        
        $newUser = $userRepo->findOrCreateByEmail($credentials['email']);
        
        $this->assertTrue($userRequest->login($newUser, $credentials));   
        
         // mocking user model
        $userModel  = $userRequest->getUser();
        
        $this->assertNotNull($userModel);
                        
        $params = [
            'id'            => 87,
            'name'          => 'FooUpdate',
            'email'         => 'foo-update@bar.com',
            'role'          => 'admin',
            ];
        
         
        $jsonReponse = $userRequest->update($userModel, $params);
        
        $this->assertNotNull($userRequest->getUser());
        
        //$this->assertArrayHasKey('headers', $userRequest->getClientOptions());
        var_dump($userRequest->getErrors());
        var_dump($userRequest->getBodyAsObject());
        $this->assertTrue($jsonReponse);        
        $this->assertTrue($userRequest->isSuccess());       
        var_dump($userRequest->getErrors());
        var_dump($userRequest->getBodyAsObject());
    }
    
      /**
     * test
     *
     * @return void
     */
    public function testSendsUpdateRequestFailed()
    {  
        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');    
        $jwtRepo->shouldReceive('isStoredByUser')->andReturn(true)->times(2);
        
        $jwtRepo->shouldReceive('getByUser')->andReturn($this->token)->times(3);
        
        
        $failedInfo = '{
            "code" : 0,
            "status":"DECLINED",
            "message":"No query results for model [BuminPspAccountModelMerchantUser]"
            }';

        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(404, ['X-Foo' => 'Bar'], $failedInfo),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($client, $jwtRepo);       
        
        $params = [
            'id'            => 1,
            'name'          => 'FooUpdate',
            'email'         => 'foo-update@bar.com',
            'role'          => 'admin',
            ];
          
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->shouldReceive('getAuthIsExist')->andReturn(true);
        $userModel->shouldReceive('getAuthCSMerchantUserId')->andReturn(1);
        $userModel->shouldReceive('getAuthCSMerchantId')->andReturn(1);
        $userModel->exists = true;        
        
        $jsonReponse = $userRequest->update($userModel, $params);
        
        $this->assertFalse($jsonReponse);
        
        $this->assertEquals($this->token, $userRequest->getUserJWT());
       
        $shouldbeOptions = ['form_params' => $params, 'headers' => ['Authorization' => $this->token]];
        
        $this->assertEquals($shouldbeOptions, $userRequest->getClientOptions());
        
        $this->assertTrue($userRequest->hasError());
        
        $this->assertEquals(json_decode($failedInfo)->message, $userRequest->apiMessage());
      
    }
    
    /**
     * test
     *
     * @return void
     */
    public function testSendsShowRequest()
    {  
        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');    
        $jwtRepo->shouldReceive('isStoredByUser')->andReturn(true)->times(2);
        
        $jwtRepo->shouldReceive('getByUser')->andReturn($this->token)->times(3);
        
        
        $successInfo = '{
            "status":"APPROVED",
            "merchantUser":{
                    "id": 59,
                    "role": "admin",
                    "email": "test@testtest.com",
                    "name": "Demo User 2",
                    "merchantId" : 3
                }
            }';

        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $successInfo),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($client, $jwtRepo);       
        
        $params = [
            'id'            => 1,         
            ];
          
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->shouldReceive('getAuthIsExist')->andReturn(true);
        $userModel->shouldReceive('getAuthCSMerchantUserId')->andReturn(1);
        $userModel->shouldReceive('getAuthCSMerchantId')->andReturn(1);
        $userModel->exists = true;        
        
        $jsonReponse = $userRequest->show($userModel, $params);
        
      
        $this->assertTrue($jsonReponse);
        
        $this->assertEquals($this->token, $userRequest->getUserJWT());
       
        $shouldbeOptions = ['form_params' => $params, 'headers' => ['Authorization' => $this->token]];
        
        $this->assertEquals($shouldbeOptions, $userRequest->getClientOptions());
        
        $this->assertFalse($userRequest->hasError()); 
    }
    
   /**
     * test
     *
     * @return void
     */
    public function testSendsChangePasswordRequest()
    {  
        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');    
        $jwtRepo->shouldReceive('isStoredByUser')->andReturn(true)->times(1);
        
        $jwtRepo->shouldReceive('getByUser')->andReturn($this->token)->times(1);
        
        
        $successInfo = '{
                "status":"APPROVED",
                "message":"Merchant User Password Updated",
                "id":59
                }';

        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $successInfo),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($client, $jwtRepo);       
        
        $params = [
            'id'            => 1,
            'password'      => 'secret',
            ];
          
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->shouldReceive('getAuthIsExist')->andReturn(true);
        $userModel->shouldReceive('getAuthCSMerchantUserId')->andReturn(1);
        $userModel->shouldReceive('getAuthCSMerchantId')->andReturn(1);
        $userModel->exists = true;        
        
        $jsonReponse = $userRequest->changePassword($userModel, $params);
             
        $this->assertTrue($jsonReponse);    
                     
        $this->assertFalse($userRequest->hasError()); 
    }
    
    /**
     * test
     *
     * @return void
     */
    public function testDeleteUserRequest()
    {  
        // mocking JSONWebToken Repository Instance
        $jwtRepo   = m::mock('App\Repositories\JSONWebToken');    
        $jwtRepo->shouldReceive('isStoredByUser')->andReturn(true)->times(1);
        
        $jwtRepo->shouldReceive('getByUser')->andReturn($this->token)->times(1);
        
        
        $successInfo = '{
                "status":"APPROVED",
                "message":"Merchant User Deleted",
                "id":"56"
                }';

        
        // Create a mock and queue one response.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $successInfo),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);     
                
        $userRequest = new User($client, $jwtRepo);       
        
        $params = [
            'id'            => 1,
            ];
          
        // mocking user model
        $userModel  = m::mock('App\User');        
        $userModel->shouldReceive('setAttribute')->andReturn();        
        $userModel->shouldReceive('getAuthIsExist')->andReturn(true);
        $userModel->shouldReceive('getAuthCSMerchantUserId')->andReturn(1);
        $userModel->shouldReceive('getAuthCSMerchantId')->andReturn(1);
        $userModel->exists = true;        
        
        $jsonReponse = $userRequest->delete($userModel, $params);
             
        $this->assertTrue($jsonReponse);    
                     
        $this->assertFalse($userRequest->hasError()); 
    }
    
        /**
     * This test methods sends real http request remote server !!!!
     */
    public function disable_testListUserWithoutMockedObjects() 
    {
        $client = \app('app.clearsettle.clients')->newClient();
        
        $jwtRepo= $this->getjwtRepoInApp();
        
        $userRequest = new User($client, $jwtRepo);
       
        $credentials = [
            'email'     => 'demo@bumin.com.tr',
            'password'  => 'cjaiU8CV',
        ];
        
           
        $userRequest->putOptions('form_params', $credentials);        
        
        $userRepo  = $this->getUserRepo();
        
        $this->callMigration();
        
        $newUser = $userRepo->findOrCreateByEmail($credentials['email']);
        
        $this->assertTrue($userRequest->login($newUser, $credentials));   
        
                // mocking user model
        $userModel  = $userRequest->getUser();
        
        $this->assertNotNull($userModel);
                        
            
         
        $jsonReponse = $userRequest->uList($userModel);
        
        var_dump($userRequest->getBodyAsObject());
        
        $this->assertNotNull($userRequest->getUser());
        
        //$this->assertArrayHasKey('headers', $userRequest->getClientOptions());
        $this->assertTrue($jsonReponse);        
        $this->assertTrue($userRequest->isSuccess());       
        
        
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
