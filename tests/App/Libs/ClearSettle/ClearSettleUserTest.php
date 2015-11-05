<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Libs\ClearSettle\User;

use Mockery as m;

class ClearSettleUserTest extends TestCase
{
        
    public function setUp() {
        
        parent::setUp();                             
    }     
    
    /**
     *
     * @return void
     */
    public function testBasicExample()
    {   
        $user = new User();
        
    }
    
    /**
     *
     * @return void
     */
    public function testMagickMethods()
    {   
        $user = new User();
        
        $user->foo = 'bar';
        
        $this->assertEquals($user->foo, 'bar');
        
        $this->assertNotTrue(isset($user['noExist']));       
        
        $this->assertNotTrue(isset($user->noExist));
        
        $user['password'] = 'secret';
        
        $this->assertEquals('secret', $user->password);
        
        $this->assertEquals('secret', $user->getAuthPassword());
    }
    
}
