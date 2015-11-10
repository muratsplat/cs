<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;


use Mockery as m;
use App\Providers\ClearSettle\ApiUserProvider as Provider; 

class ProvidersClearSettleApiUserProviderTest extends TestCase
{
       
    public function setUp() {
        parent::setUp();      
    }    
    
    /**
     * @return void
     */
    public function testBasicExample()
    {  
        
        $clientManager  = m::mock('App\Libs\ClearSettle\Resource\ApiClientManager');
        
        $repo           = m::mock('App\Contracts\Repository\User');        
        
        $mockedUser     = m::mock('App\User');
        
        $repo->shouldReceive('findOrCreateByEmail')->andReturn($mockedUser)->times(1);
        
        $userProvider   = new Provider($clientManager, $repo);
        
        $credentials    = ['email' => 'foo@bar.com', 'password' => 'secret'];
        $authedUser     = $userProvider->retrieveByCredentials($credentials);
        
        
        
        
    }
}
