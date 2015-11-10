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
        $this->container->shouldReceive("make")->times(1);
        
        $manager = new Manager($this->container);
        
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
        
        $this->container->shouldReceive('make')->with("config")->andReturn($config);
        
        $manager = new Manager($this->container);
        
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
    
        
    /**
     * Functional test
     * 
     * @test
     */
    public function testWithRealObjectInIOC() 
    {
        $manager = \app('app.clearsettle.clients');
        
        $client = $manager->newClient();    
            
        $response = $client->post(
                'merchant/user/login', 
                [
                    'verify'        => true,
                    'form_params'   => [
                        
                        'email'     => 'demo@bumin.com.tr',
                        'password'  => 'cjaiU8CV'
                    ],
                
                ]
                
                );  
        
        $this->assertEquals( 200, $response->getStatusCode() );        
            
    }

}
