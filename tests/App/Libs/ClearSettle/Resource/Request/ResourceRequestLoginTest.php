<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Libs\ClearSettle\Resource\Request\Login;

use Mockery as m;

class ResourceRequestLoginTest extends TestCase
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
        $user   = m::mock('App\Libs\ClearSettle\User');
        
        $client = m::mock('GuzzleHttp\Client');        
        
        $request = new Login($user, $client);       
    }
    
   
}
