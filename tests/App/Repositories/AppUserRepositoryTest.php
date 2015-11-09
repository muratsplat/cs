<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as m;
use App\Repositories\User;

class AppUserRepositoryTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {        
        $repo = \app('App\Contracts\Repository\User');
        
        $this->assertNotNull($repo);       
    }
    
    public function testAllMethods()
    {
        $model = m::mock('App\User');
        
        $repo = new User($model);      
    }
    
    
    /**
     * Functional test
     */
    public function testRepositoryOnRealDBEnv()
    {
        /**
         * The tests should works only in sqlite on memory.. 
         */
        if ( ! $this->isSqliteOnMemory()) { return; }
        
        $this->callMigration();
        
        $attr = ['email' => 'foo@bar.com'];
        
        $repo = $this->userRepo();
        
        $user  = $repo->create($attr);
        
        $this->assertTrue($user->exists);
        
        $this->assertNotEmpty($repo->all());
               
        $tryUser  = $repo->findOrCreateByEmail($attr['email']);
        
        $this->assertEquals(1, $repo->count());
        
        $this->assertEquals($attr['email'], $tryUser->email);        
    }    
    
    /**
     * 
     * @return \App\Repositories\User
     */
    private function userRepo()
    {
        return \app('App\Contracts\Repository\User');        
    }
}
