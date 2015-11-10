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
    
    /**
     * Unit Test
     *
     * @return void
     */
    public function testLoginUnsuccess()
    {       
        $credentials = ['email' => 'foo@bar.com', 'password' => 'secret'];        
        
        $jwtRepo = m::mock('App\Contracts\Repository\JSONWebToken');
        
        $user   = m::mock('App\Contracts\Auth\ClearSettleAuthenticatable');
        
        $userRequest = m::mock('App\Libs\ClearSettle\Resource\Request\User');
        
        $userRequest->shouldReceive('login')->times(1)->with($user, $credentials)->andReturn(false);
        
        $loginService = new ApiLogin($jwtRepo, $userRequest);  
        
        $this->assertFalse($loginService->login($user, $credentials));        
    }
    
    /**
     * Functional Test
     *
     * @return void
     */
    public function testProviderTest()
    {       
        $loginService = \app('app.clearsettle.login');  
        
        $this->assertNotNull($loginService);
    }
    
    /**
     * Functional Test
     *
     * @return void
     */
    public function testLoginTryWithoutMockedObjectsUnsuccess()
    {       
        
        $loginService = \app('app.clearsettle.login');          
        
        $wrongCredentials = ['email' => 'foo@bar.com', 'password' => 'secret'];   
        
        $user = m::mock('App\Contracts\Auth\ClearSettleAuthenticatable');
        
        $this->assertFalse($loginService->login($user, $wrongCredentials));     
    }
    
        
    /**
     * Functional Test
     *
     * @return void
     */
    public function testLoginTryWithoutMockedObjectsSuccess()
    {     
        
        $loginService = \app('app.clearsettle.login');          
        
        $wrongCredentials = [
                        'email'     => 'demo@bumin.com.tr',
                        'password'  => 'cjaiU8CV',
            ];
        
        $user = m::mock('App\Contracts\Auth\ClearSettleAuthenticatable');
        
        $this->assertTrue($loginService->login($user, $wrongCredentials));     
    }
    
   
    
}
