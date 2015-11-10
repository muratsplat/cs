<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use GuzzleHttp\Client;

class ApiRequestTest extends TestCase
{
    
    /**
     *
     * @var \GuzzleHttp\Client
     */
    private $client;   
    
    public function setUp() {
        parent::setUp();    
        
        $this->client = new Client([
            
            // Base URI is used with relative requests
            'base_uri' => 'https://testreportingapi.clearsettle.com/api/v3/',
            // You can set any number of default request options.
            'timeout'  => 6
           
        ]);        
    }
    
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function disable_testBasicExample()
    {      
        
        $response = $this->client->post(
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
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function disable_testWrongPassword()
    {      
        
        $response = $this->client->post(
                'merchant/user/login', 
                [
                    'verify'        => true,
                    'form_params'   => [
                        
                        'email'     => 'demo@bumin.com.tr',
                        'password'  => 'secret'
                    ],
                
                ]
                
                );   
        
        $statusCode = $response->getStatusCode() ;
     
        $this->assertEquals( 200, $statusCode );       
       
    }
    
    public function testNeededByPHPUnitForNoError() {}
}
