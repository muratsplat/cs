<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;


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
   
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {           
        $userM   = m::mock('App\Libs\ClearSettle\User');
        
        $userM->shouldReceive('getAuthEmail')->andReturn('foo@bar.com');
        
        $userM->shouldReceive('getAuthPassword')->andReturn('secret');
        
        $client  = m::mock('GuzzleHttp\Client');        
        
        $options = ['form_params' => ['email' => 'foo@bar.com', 'password' => 'secret']];
        
        $client->shouldReceive('request')->with('POST', '/merchant/user/login',$options)->andReturn();
        
        $userRequest = new User($userM, $client);       
        
        $params  = $userRequest->getRequestParams('login');
        
        $this->assertEquals(['POST' => '/merchant/user/login'], $params);
        
        //$userRequest->login();
       
    }
    
   
}
