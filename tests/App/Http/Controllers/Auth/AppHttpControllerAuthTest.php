
<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as m;

class ExampleTest extends TestCase
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
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/login')
             ->see('Login');
    }
    
    /**
     * 
     *
     * @return void
     */
    public function testFirstLoginTrySuccess()
    {       
             
        $user = m::mock('App\Contracts\Auth\ClearSettleAuthenticatable');
        
        $user->shouldReceive('getAuthIdentifier')->times(1)->andReturn(1);
        
         // mocked user repository
        $userRepo = m::mock('App\Contracts\Repository\User');
        
        $userRepo->shouldReceive('findOrCreateByEmail')->andReturn($user)->times(1);       
        
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
        
        $this->visit('/')
             ->see('You are log in System !');
        
        $this->assertTrue(\Auth::check());       
    }
    
    /**
     * 
     *
     * @return void
     */
    public function testFirstLoginTrySuccessAndThanLogout()
    {       
             
        $user = m::mock('App\Contracts\Auth\ClearSettleAuthenticatable');
        
        $user->shouldReceive('getAuthIdentifier')->times(1)->andReturn(1);
        
        $user->shouldReceive('setRememberToken')->times(2)->andReturn(null);
        
        $user->shouldReceive('save')->times(1)->andReturn(null);
        
         // mocked user repository
        $userRepo = m::mock('App\Contracts\Repository\User');
        
        $userRepo->shouldReceive('findOrCreateByEmail')->andReturn($user)->times(1);       
        
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
        
        $this->visit('/')
             ->see('You are log in System !');
        
        $this->assertTrue(\Auth::check());
        
        $res = $this->call('GET', '/logout');
        
        $this->assertResponseStatus(302);
                
        $this->visit('/')
             ->dontSee('You are log in System !');       
    }
    
    /**
     * 
     *
     * @return void
     */
    public function testFirstLoginTryUnSuccess()
    {       
             
        $user = m::mock('App\Contracts\Auth\ClearSettleAuthenticatable');
                
         // mocked user repository
        $userRepo = m::mock('App\Contracts\Repository\User');
        
        $userRepo->shouldReceive('findOrCreateByEmail')->andReturn($user)->times(1);       
        
        $this->app->instance('App\Contracts\Repository\User', $userRepo);               
        
        $loginServices = m::mock('\App\Services\ClearSettle\ApiLogin');
        
        $loginServices->shouldReceive('login')->andReturn(false)->times(1);
        // mocked login services
        $this->app->instance('app.clearsettle.login', $loginServices);
        
        $post = [
            
            '_token'    => csrf_token(),
            'email'     => 'test@foo.com',
            'password'  => 'secret',
        ];
        
        
        $res = $this->call('POST', '/login', $post);            
       
        $this->assertResponseStatus(302);   
        
        $this->assertRedirectedTo('/login');
        
    }
}
