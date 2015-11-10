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
        $jwtRepo = m::mock('App\Contracts\Repository\JSONWebToken');
        
        $userRequest = m::mock('App\Libs\ClearSettle\Resource\Request\User');
        
        $login = new ApiLogin($jwtRepo, $userRequest);  
    }
    
    /**
     * Unit Test
     *
     * @return void
     */
    public function testLogin()
    {       
        $credentials = ['email' => 'foo@bar.com', 'password' => 'secret'];        
        
        $jwtRepo = m::mock('App\Contracts\Repository\JSONWebToken');
        
        $user   = m::mock('App\Contracts\Auth\ClearSettleAuthenticatable');
        
        $userRequest = m::mock('App\Libs\ClearSettle\Resource\Request\User');
        
        $userRequest->shouldReceive('login')->times(1)->with($user, $credentials)->andReturn(true);
        
        
        
        $loginService = new ApiLogin($jwtRepo, $userRequest);  
        
        $this->assertTrue($loginService->login($user, $credentials));
        
    }
}
