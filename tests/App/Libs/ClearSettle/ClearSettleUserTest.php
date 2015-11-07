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
      
    }
    
    /**
     * Functional Test
     *
     * @return void
     */
    public function testUserStoreSesstion()
    {   
        $user = new User();    
        
        $user->foo = 'bar';        
        
        $jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXA
iOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298';
        
        $user->setJWTToken($jwt);
       
        \Session::put('test', $user);
        
        $storedUser = \Session::get('test');
        
        $this->assertEquals($jwt, $storedUser->getJWTToken());
        
        $this->assertEquals('bar', $storedUser->foo);      
    }
    
    /**
     * Functional Test
     *
     * @return void
     */
    public function testUserStoreCache()
    {   
        $user = new User();    
        
        $user->foo = 'bar';        
        
        $jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXA
iOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298';
        
        $user->setJWTToken($jwt);
       
        \Cache::add('test', $user, 1);
        
        $storedUser = \Cache::get('test');
        
        $this->assertEquals($jwt, $storedUser->getJWTToken());
        
        $this->assertEquals('bar', $storedUser->foo);      
    }
    
}
