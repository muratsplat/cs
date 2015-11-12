
<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as m;

class AppHttpControllerTransactionApiTest extends TestCase
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
    
    private function beLogin()
    {
         $user = $this->getMockedUser();
        
        $user->shouldReceive('getAuthIdentifier')->times(1)->andReturn(1);
        
        $user->shouldReceive('setRememberToken')->andReturnNull();
        
        $user->shouldReceive('save')->andReturnNull();
        
        $user->shouldReceive('authHasCSJWT')->andReturn(true);
        
        $email = 'foo@bar.com';
        $user->shouldReceive('getAttribute')->with('email')->andReturn($email);
        
        \Auth::login($user);
    }
    
    /**
     * 
     *
     * @return void
     */
    public function testIndex()
    {    
        $this->beLogin();        
        
        $this->visit('/console/transaction')
                ->see('I am Index');
       
        $this->assertResponseStatus(200);           
    }
    
}
