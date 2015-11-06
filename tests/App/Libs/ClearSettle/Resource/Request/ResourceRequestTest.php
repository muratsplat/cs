<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
//use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Handler\MockHandler;
//use GuzzleHttp\Exception\RequestException;
use App\Libs\ClearSettle\Resource\Request\Request;

use Mockery as m;

class ResourceRequestUserTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface 
     */
    protected $container;
    
    
    public function setUp() {
        
        parent::setUp();               
                       
    }
   
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {           
        $userM   = m::mock('App\Libs\ClearSettle\User');   
        // the example of login sson response
        $responseBody = '{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjaGFudFVzZXJJZCI6MSwicm9sZSI6ImFkbWluIiwibWVyY2hhbnRJZCI6MSwic3ViTWVyY2hhbnRJZHMiOltdLCJ0aW1lc3RhbXA
iOjE0NDQzODk4ODB9.zPxVu4fkRqIy1uG2fO3X2RbxiI4otK_HG7M4MMTB298","status":"APPROVED"}';
        
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $responseBody),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);   
        
        $fooRquest = new FooRequest($userM, $client);
        
        $fooRquest->request('create', []);
        
        $this->assertTrue($fooRquest->isReady());
        
        $this->assertTrue($fooRquest->isJSON());
        
        $this->assertTrue($fooRquest->isApproved());       
    }
    
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testStatusCode500Declined()
    {           
        $userM   = m::mock('App\Libs\ClearSettle\User');   
        // the example of login sson response
        $responseBody = '{"message":"Bla blaa","status":"DECLINED"}';
        
        
        
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(500, ['X-Foo' => 'Bar'], $responseBody),      
        ]);
        
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);   
        
        $fooRquest = new FooRequest($userM, $client);
        try {
            
             $fooRquest->request('create', []);
            
        } catch (GuzzleHttp\Exception\ServerException $ex) {

        }
        $this->assertTrue($fooRquest->isReady());
        
        $this->assertFalse($fooRquest->isApproved());    
        
        $json = $fooRquest->convertResponseBodyToJSON();
        
        $this->assertTrue($fooRquest->isJSON());
        
        $this->assertEquals($json->status, 'DECLINED');        
       
    }
    
   
}



class FooRequest extends Request {    
    
    protected $requests = [
      //method              http verb   route
        'create'         =>  ['POST' => '/merchant/user/login'],
          
    ]; 
    
    
    public function create()        
    {        
        try {                
               
            $options    = ['Foo' => 'Bar'];
            // sync request, not async !!!

            if ( $this->request('create', $options)->isApproved() ) {

                return $this->convertResponseBodyToJSON();                      

            }   

            if ( $this->isReady() && $this->isJSON() ) {

                return $this->convertResponseBodyToJSON();                                    
            }


            return null;               

        } catch (Exception $exc) {

            $this->catchAndReport($exc);
        }

        return null;
        
        
    }
    
    
}
