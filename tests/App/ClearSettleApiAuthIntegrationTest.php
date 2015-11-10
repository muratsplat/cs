<?php

//use Illuminate\Foundation\Testing\WithoutMiddleware;
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;

use GuzzleHttp\Client;

class ClearSettleApiAuthIntegrationTest extends TestCase
{
    
    
    public function setUp() {
        parent::setUp();    
    
    }
    
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function disable_testTryValidate()
    {
        $this->callMigration();        
        
        $credentials = [            

                'email'     => 'demo@bumin.com.tr',
                'password'  => 'cjaiU8CV'
         ];
        
        $this->assertTrue(\Auth::validate($credentials, true));      
       
    }
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function disable_testValidateTryWithWrongSecret()
    {
        $this->callMigration();        
        
        $credentials = [            

                'email'     => 'demo@bumin.com.tr',
                'password'  => 'failed'
         ];
        
        $this->assertFalse(\Auth::validate($credentials));             
    }    
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function disable_testLogin()
    {
        $this->callMigration();        
        
        $credentials = [            

                'email'     => 'demo@bumin.com.tr',
                'password'  => 'cjaiU8CV'
         ];
        
        $this->assertTrue(\Auth::attempt($credentials, true, true));
        
        $this->assertTrue(\Auth::check());     
        
        $user  = \Auth::getUser();
        
        $token = $this->jwtRepo()->getByUser($user);
        
        $this->assertNotEmpty($token);               
    }
    
    /**
     * 
     * @return \App\Contracts\Repository\JSONWebToken
     */
    private function jwtRepo()
    {
        return \app('App\Contracts\Repository\JSONWebToken');
        
    }
    
    
    public function testNeededByPHPUnitForNoError() {}
}
