<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as m;
use App\Repositories\UserApi;

class AppUserApiRepositoryTest extends TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function testBasicExample()
    {        
        $clientManager  = m::mock('\App\Libs\ClearSettle\Resource\ApiClientManager');
        $jwtRepo        = m::mock('\App\Contracts\Repository\JSONWebToken');
        $repo = new UserApi($clientManager, $jwtRepo);   
    }
    
    /**
     * 
     * @return \Mockery
     */
    private function mockedJwtRepo()
    {
        return m::mock('\App\Contracts\Repository\JSONWebToken');
    }    
    
    /**
     * 
     * @return \Mockery
     */
    private function mockedClientManager()
    {
        return m::mock('\App\Libs\ClearSettle\Resource\ApiClientManager');
    }
    
    /**
     * 
     * @return \Mockery
     */
    private function mockedUser()
    {
        return m::mock('\App\User');
    }
    
    /**
     * 
     * @return \Mockery
     */
    private function mockedUserRequest()
    {
        return m::mock('\App\Libs\ClearSettle\Resource\Request\User');
    }
    
    public function testCreate() 
    {
        $clientManager  = $this->mockedClientManager();
        
        $userRequest = $this->mockedUserRequest();
        
        $user = $this->mockedUser();
        
        $userRequest->shouldReceive('create')->with($user, [])->andReturn(true);
        
        $userRequest->shouldReceive('hasError')->andReturn(false);
        
        $clientManager->shouldReceive('createNewRequest')
                ->andReturn($userRequest); 
        
        $jwtRepo        = $this->mockedJwtRepo();
        
        $repo = new UserApi($clientManager, $jwtRepo);  
        
        $attributes = [];
        
        $repo->setUser($user);
        
        $repo->create($attributes);
        
        $this->assertFalse($repo->getRequest()->hasError());
        
    }
    
}
