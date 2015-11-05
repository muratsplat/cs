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
        
        $clientManager = m::mock('App\Libs\ClearSettle\Resource\ApiClientManager');
        
        $UserProvider = new Provider($clientManager);       
    }
}
