<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as m;
use App\Repositories\JSONWebToken;

class AppJSONWebTokenRepositoryTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {        
        $cache = \app('cache.store');
        
        $repo = new JSONWebToken($cache);
    }
    
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testIDTest()
    {        
        $cache  = \app('cache.store');
        
        $repo   = new JSONWebToken($cache);
        
        $user   = factory(App\User::class)->make();
        
        $user->exists = true;
        
        $this->assertNotNull($user);
        
        $user->id = 1;        
        
        $this->assertEquals(1, $user->id);        
        
        $jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXA
iOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298';
        
        $this->assertFalse($repo->isStoredByUser($user));
        
        $repo->storeByUser($user, $jwt);
        
        $this->assertEquals($jwt, $repo->getByUser($user));
        
        $this->assertTrue($repo->isStoredByUser($user));
        
        $user->id = 99;
        
        $this->assertEquals(null, $repo->getByUser($user));
        
        $this->assertEquals('foo', $repo->getByUser($user, 'foo'));
        
        $user->id = 1;
        
        $this->assertEquals($jwt, $repo->getByUser($user));        
        
        $repo->setExpiration(99);
        
        $this->assertEquals(99, $repo->getExpiration());

        try {

            $repo->setExpiration(-1);

            $this->assertTrue(false);
            
        } catch (\InvalidArgumentException $ex) {}     
        
    }
    
    
    public function testProviderTest() 
    {
        $repo = \app('App\Contracts\Repository\JSONWebToken');
        
        $this->assertInstanceOf('App\Repositories\JSONWebToken', $repo);
        
        $user   = factory(App\User::class)->make();
        
        $user->exists = true;
        
        $this->assertNotNull($user);
        
        $user->id = 1;        
        
        $this->assertEquals(1, $user->id);        
        
        $jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXA
iOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298';
        
        $this->assertFalse($repo->isStoredByUser($user));
        
        $repo->storeByUser($user, $jwt);
        
        $this->assertEquals($jwt, $repo->getByUser($user));
        
        $this->assertTrue($repo->isStoredByUser($user));      
    }
}
