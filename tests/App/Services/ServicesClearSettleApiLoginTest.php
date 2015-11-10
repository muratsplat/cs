<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\ClearSettle\ApiLogin;
use Mockery as m;


class ServicesClearSettleApiLoginTest extends TestCase
{
    /**
     * Unit Test
     *
     * @return void
     */
    public function testBasic()
    {
        $clients = m::mock('App\Libs\ClearSettle\Resource\ApiClientManager');        
        $jwtRepo = m::mock('App\Contracts\Repository\JSONWebToken');
        
        $userRequest = m::mock('App\Libs\ClearSettle\Resource\Request\User');
        
        $login = new ApiLogin($clients, $jwtRepo, $userRequest);  
    }
    
    /**
     * Unit Test
     *
     * @return void
     */
    public function testLogin()
    {
        $clients = m::mock('App\Libs\ClearSettle\Resource\ApiClientManager');  
        
        $guzzleClient = m::mock('GuzzleHttp\Client');
        
        $clients->shouldReceive('newClient')->times(1)->andReturn($guzzleClient);
        
        $jwtRepo = m::mock('App\Contracts\Repository\JSONWebToken');
        
        $user   = m::mock('App\Contracts\Auth\ClearSettleAuthenticatable');
        
        $userRequest = m::mock('App\Libs\ClearSettle\Resource\Request\User');
        
        $credentials = ['email' => 'foo@bar.com', 'password' => 'secret'];
        
        $loginService = new ApiLogin($clients, $jwtRepo, $userRequest);  
        
        $this->assertTrue($loginService->login($user, $credentials));
        
    }
}
