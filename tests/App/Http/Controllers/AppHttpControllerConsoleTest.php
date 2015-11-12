
<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as m;

class AppHttpControllerAuthTest extends TestCase
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
     * 
     * @return \Mockery
     */
    private function getMockedUser()
    {
        return m::mock('App\User');
    }
    
    /**
     * 
     * @return \Mockery
     */
    private function getMockedAuthUser()
    {
        return m::mock('App\Contracts\Auth\ClearSettleAuthenticatable');
        
    }
    
    /**
     * 
     * @return \Mockery
     */
    private function getMockedUserRepo()
    {
        return  m::mock('App\Contracts\Repository\User');        
    }
    
    /**
     * 
     * @return \Mockery
     */
    private function getMockedLoginService()
    {
        return  m::mock('\App\Services\ClearSettle\ApiLogin');
    }   
    
    /**
     * 
     *
     * @return void
     */
    public function testIndex()
    {       
        $user = $this->getMockedUser();
        
        $user->shouldReceive('getAuthIdentifier')->times(1)->andReturn(1);
        
        $user->shouldReceive('setRememberToken')->andReturnNull();
        
        $user->shouldReceive('save')->andReturnNull();
        
        $user->shouldReceive('authHasCSJWT')->andReturn(true);
        
        $email = 'foo@bar.com';
        $user->shouldReceive('getAttribute')->with('email')->andReturn($email);
        
        \Auth::login($user);
        
        
        $this->visit('/console/welcome')
                ->see('foo@bar.com');
       
        $this->assertResponseStatus(200);           
    }
    
}
