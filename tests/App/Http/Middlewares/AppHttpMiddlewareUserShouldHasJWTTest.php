
<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as m;

class AppHttpMiddlewareUserShouldHasJWTTest extends TestCase
{
    // disable middleWares
   // use WithoutMiddleware;
    
    //use DatabaseMigrations;
    
    
   public function setUp() {
       parent::setUp();
              
       \Session::start();      
       
   }
   
   public function tearDown() {
       parent::tearDown();
       
       m::close();
   }
   
    /**
     * @return void
     */
    public function testFirstLoginTryUserShouldHasJWT()
    {       
             
        $user = m::mock('App\User');
        
        $user->shouldReceive('getAuthIdentifier')->times(1)->andReturn(1);
        
        $user->shouldReceive('authHasCSJWT')->andReturn(true)->times(1);
        
        $user->shouldReceive('getAttribute')->with('email')->andReturn('foo@bar.com');        
                
         // mocked user repository
        $userRepo = m::mock('App\Contracts\Repository\User');
        
        $userRepo->shouldReceive('findOrCreateByEmail')->andReturn($user)->times(1);       
        
        $userRepo->shouldReceive('getModel')->andReturn($user);
        
        $this->app->instance('App\Contracts\Repository\User', $userRepo);               
        
        $loginServices = m::mock('\App\Services\ClearSettle\ApiLogin');
        
        $loginServices->shouldReceive('login')->andReturn(true)->times(2);
        // mocked login services
        $this->app->instance('app.clearsettle.login', $loginServices);
        
        $post = [
            
            '_token'    => csrf_token(),
            'email'     => 'test@foo.com',
            'password'  => 'secret',
        ];
        
        
        $res = $this->call('POST', '/login', $post);            
       
        $this->assertResponseStatus(302);   
        
        $this->assertRedirectedTo('/console/welcome');
        
        $res1 = $this->call('GET', '/console/welcome');       
        
        $this->assertResponseStatus(200);       
    }
    
    /**
     * @return void
     */
    public function testFirstLoginTryUserShouldHasJWTWithNoJWT()
    {       
             
        $user = m::mock('App\User');
        
        $user->shouldReceive('getAuthIdentifier')->times(1)->andReturn(1);
        
        $user->shouldReceive('authHasCSJWT')->andReturn(false)->times(1);
        
        $user->shouldReceive('setRememberToken')->andReturn();
        
        $user->shouldReceive('save')->andReturn();
        
         // mocked user repository
        $userRepo = m::mock('App\Contracts\Repository\User');
        
        $userRepo->shouldReceive('findOrCreateByEmail')->andReturn($user)->times(1);       
                
        $userRepo->shouldReceive('getModel')->andReturn($user);
        
        $this->app->instance('App\Contracts\Repository\User', $userRepo);               
        
        $loginServices = m::mock('\App\Services\ClearSettle\ApiLogin');
        
        $loginServices->shouldReceive('login')->andReturn(true)->times(2);
        // mocked login services
        $this->app->instance('app.clearsettle.login', $loginServices);
        
        $post = [
            
            '_token'    => csrf_token(),
            'email'     => 'test@foo.com',
            'password'  => 'secret',
        ];
        
        
        $res = $this->call('POST', '/login', $post);            
       
        $this->assertResponseStatus(302);   
        
        $this->assertRedirectedTo('/console/welcome');
               
        $res1 = $this->call('GET', '/console/welcome');
        
        $this->assertResponseStatus(302);
        
        $this->assertRedirectedTo('/login');
        
        $this->assertTrue(\Session::has('neededLogin'));        
    }
}
