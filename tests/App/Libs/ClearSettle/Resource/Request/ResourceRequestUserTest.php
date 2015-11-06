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
        
        $client  = m::mock('GuzzleHttp\Client');        
        
        $userRequest = new User($userM, $client);       
        
        $params  = $userRequest->getRequestParams('login');
        
        $this->assertEquals(['POST' => '/merchant/user/login'], $params);
        
        $userRequest->login();
       
    }
    
   
}
