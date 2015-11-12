<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Libs\ClearSettle\Resource\ApiClientManager as Manager;

use Mockery as m;

class ApiClientManagerTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface 
     */
    protected $container;
    
    
    public function setUp() {
        
        parent::setUp();       
        
        $this->container = m::mock('\Illuminate\Contracts\Container\Container');
                       
    }
    
    /**
     * 
     * @return \Mockery\MockInterface
     */
    private function mockedConfig()
    {
        return m::mock('Illuminate\Contracts\Config\Repository');
    }
    
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {   
        $jwtRepo = m::mock('App\Contracts\Repository\JSONWebToken');
        
        $config  = $this->mockedConfig();
        
        $manager = new Manager($this->container, $jwtRepo, $config);
        
        $this->assertNotNull($manager);        
        
    }
    
    /**
     *
     * @return void
     */
    public function testNewClient()
    {   
        $config = $this->mockedConfig();
        
        $config->shouldReceive('get')->with('api.default', null)->andReturn('Foo');
        
        $config->shouldReceive('get')->with('api.apis.Foo', [])->andReturn(
                [
                    'base_url'  => 'https//www.google.com',
                    'verify'    => true,
                    'timeout'   => 3,
                ]
        );
       
        
        $jwtRepo = m::mock('App\Contracts\Repository\JSONWebToken');
        
        $manager = new Manager($this->container, $jwtRepo, $config);
        
        $client = $manager->newClient();
        
        $this->assertNotNull($client);
        
        $this->assertInstanceOf('GuzzleHttp\Client', $client);
    }
    
    /**
     * @test
     */
    public function testExistInIOC() 
    {
        $manager = \app('app.clearsettle.clients');
        
        $this->assertNotNull($manager);    
    }
    
        
//    /**
//     * Functional test
//     * 
//     * @test
//     */
//    private function disable_WithRealObjectInIOC() 
//    {
//        $manager = \app('app.clearsettle.clients');
//        
//        $client = $manager->newClient();    
//            
//        $response = $client->post(
//                'merchant/user/login', 
//                [
//                    'verify'        => true,
//                    'form_params'   => [
//                        
//                        'email'     => 'demo@bumin.com.tr',
//                        'password'  => 'secret'
//                    ],
//                
//                ]
//                
//                );  
//        
//        $this->assertEquals( 200, $response->getStatusCode() );        
//            
//    }
    
    /**
     * test
     *
     * @return void
     */
    public function testRequest()
    {           
        
        $jwtRepo = m::mock('App\Contracts\Repository\JSONWebToken');
        
        $config  = $this->mockedConfig();
        
        $config->shouldReceive('get')->with('api.default', null)->andReturn('Foo');
        
        $config->shouldReceive('get')->with('api.apis.Foo', [])->andReturn(
                [
                    'base_url'  => 'https//www.google.com',
                    'verify'    => true,
                    'timeout'   => 3,
                ]
        );
        
        
        $manager = new Manager($this->container, $jwtRepo, $config);
        
        $this->assertNotNull($manager->createNewRequest('user'));        
        
    }
    
}
